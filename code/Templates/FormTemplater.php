<?php
namespace WPOrbit\Forms\Templates;

use WPOrbit\Forms\Form;
use WPOrbit\Forms\Inputs\FormInput;

abstract class FormTemplater
{
    /**
     * @var Form
     */
    protected $form;

    public function renderField( FormInput $field )
    {
        $output = '';

        $output .= "\t" . '<div class="form-field">' . "\n";

        // Set the label.
        if ( $field->getLabel() ) {
            $output .= "\t\t" . '<label>' . $field->getLabel() . '</label>' . "\n";
        }

        $output .= "\t\t" . $field->render() . "\n";

        $output .= "\t" . '</div>' . "\n";

        return $output;
    }

    public function renderItems()
    {
        $output = [];

        // Loop through this form's fields.
        foreach( $this->form->fields->all() as $field )
        {
            /** @var $field FormInput */
            $output[] = $this->renderField( $field );
        }

        return implode( '', $output );
    }

    public function getFormHeader()
    {
        $methodString = $this->form->method ? ' method="' . $this->form->method . '"' : '';;
        $actionString = $this->form->action ? ' action="' . $this->form->action . '"' : '';
        $idString = $this->form->id ? ' id="' . $this->form->id . '"' : '';

        $output =
            "<form" .
            $idString .
            $actionString .
            $methodString .
            ">\n";

        return $output;
    }

    public function getFormFooter()
    {
        $string = "\t" . wp_nonce_field( 'form-' . $this->form->id, '_nonce_' . $this->form->id, true, false ) . "\n";
        return $string . '</form>';
    }

    public function renderForm()
    {
        return $this->renderItems();
    }

    public function __construct( Form $form )
    {
        $this->form = $form;
    }
}