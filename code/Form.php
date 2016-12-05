<?php
namespace WPOrbit\Forms;

use WPOrbit\Forms\Templates\DefaultFormTemplater;
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
        $this->templater = new DefaultFormTemplater( $this );
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

    public function saveFieldCallback( $field )
    {
        return;
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
}