<?php
namespace WPOrbit\Forms\Inputs;

/**
 * A basic text input field.
 *
 * Class TextInput
 * @package WPOrbit\Forms\Inputs
 */
class TextInput extends FormInput
{
    protected function prepareAttributes()
    {
        parent::prepareAttributes();
        if ( $this->value ) {
            $this->setAttribute( 'value', $this->value );
        }
    }

    public function __construct( $args = [] )
    {
        parent::__construct( $args );
        $this->type = 'text';
        $this->attributes->setAttribute( 'type', 'text' );
    }
}