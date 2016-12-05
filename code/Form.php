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

    public function save()
    {
        $nonceKey = '_nonce_' . $this->id;
        $nonceAction = 'form-' . $this->id;

        if ( ! isset( $_POST[$nonceKey] ) ) {
            throw new \Exception( "No security token supplied." );
        }

        if ( ! wp_verify_nonce( $_POST[$nonceKey], $nonceAction ) ) {
            throw new \Exception( "Invalid security token supplied." );
        }

    }
}