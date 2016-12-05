<?php
namespace WPOrbit\Forms;

use WPOrbit\Forms\Templates\DefaultFieldTemplater;
use WPOrbit\Forms\Templates\FormTemplater;

class Form
{
    /**
     * @var FormFields
     */
    public $fields;

    /**
     * @var FormTemplater
     */
    public $templater;

    /**
     * @var string The form ID.
     */
    public $id;

    /**
     * @var string The form method.
     */
    public $method = 'POST';

    /**
     * @var string The form action.
     */
    public $action = '';

    public function __construct( $id = '' )
    {
        $this->id = $id;
        $this->fields = new FormFields();
        $this->templater = new DefaultFieldTemplater( $this );
        $this->setUp();
    }

    public function setUp()
    {
        // Do something in extensions.
    }

    public function setTemplater( $templaterClass )
    {
        $this->templater = new $templaterClass( $this );
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->templater->renderForm();
    }

    /**
     * Get the form field data necessary for updating.
     * @return array
     */
    public function getSaveFields()
    {
        $output = [
            'nonceKey' => '_nonce_' . $this->id,
            'nonceAction' => 'form-' . $this->id,
            'fields' => []
        ];

        foreach( $this->fields->all() as $field )
        {
            $output['fields'][] = [
                'type' => $field->getType(),
                'key' => $field->getName(),
            ];
        }

        return $output;
    }

    /**
     * @param int $postId When the form is used in a post context (like a meta box), we can
     * preload the form field values.
     */
    public function loadPostMeta($postId)
    {
        // Set field values before rendering.
        foreach( $this->fields->all() as $field )
        {
            switch( $field->getType() )
            {
                default:
                    // Set the field value.
                    $field->setValue( get_post_meta( $postId, $field->getName(), true ) );
                    break;
            }
        }
    }

    public function savePostMeta($postId)
    {
        $fields = $this->getSaveFields();

        if ( ! isset( $_POST[ $fields['nonceKey'] ] ) )
        {
            throw new \Exception( "No security token supplied." );
        }

        if ( ! wp_verify_nonce( $_POST[ $fields['nonceKey'] ], $fields['nonceAction'] ) )
        {
            throw new \Exception( "Invalid security token supplied." );
        }

        foreach( $this->fields->all() as $field )
        {
            switch ( $field->getType() )
            {
                default:
                    if ( isset( $_POST[$field->getName()] ) ) {
                        update_post_meta( $postId, $field->getName(), $_POST[$field->getName()] );
                    }
                    break;
            }
        }
    }
}