<?php
namespace WPOrbit\Forms\Inputs;

/**
 * A basic text input field.
 *
 * Class TextInput
 * @package WPOrbit\Forms\Inputs
 */
class ButtonInput extends FormInput
{
    protected function prepareAttributes()
    {
        parent::prepareAttributes();
        if ( $this->value ) {
            $this->setAttribute( 'value', $this->value );
            $this->setAttribute( 'class', 'button' );
        }
    }

    public function __construct( $args = [] )
    {
        parent::__construct( $args );
        $this->type = 'button';
        $this->attributes->setAttribute( 'type', isset( $args['type'] ) ? $args['type'] : 'button' );
    }

    /**
     * @return string Get input element HTML.
     */
    public function render()
    {
        // Verify the input field is valid.
        $this->validateInput();

        // Return the rendered HTML.
        return "<button {$this->getAttributes(['value'])}>{$this->getValue()}</button>";
    }
}