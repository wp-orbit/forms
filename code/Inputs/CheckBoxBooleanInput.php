<?php
namespace WPOrbit\Forms\Inputs;

class CheckBoxBooleanInput extends FormInput
{
    protected function prepareAttributes()
    {
        parent::prepareAttributes();
    }

    public function __construct( $args = [] )
    {
        parent::__construct( $args );

        $this->type = 'checkbox-boolean';
        $this->setAttribute( 'type', 'checkbox' );
        $this->setValue( 'true' );

        // Check the box?
        if ( isset( $args['checked'] ) && true == $args['checked'] ) {
            $this->setAttribute( 'checked', 'checked' );
        }
    }

    /**
     * @return string Get input element HTML.
     */
    public function render()
    {
        // Verify the input field is valid.
        $this->validateInput();

        $this->setValue('true');

        // Return the rendered HTML.
        return "<input {$this->getAttributes()}/>";
    }
}