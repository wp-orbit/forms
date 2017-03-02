<?php
namespace WPOrbit\Forms\Inputs;

class DateTimeInput extends FormInput
{
    protected function prepareAttributes()
    {
        parent::prepareAttributes();
        if ( $this->value )
        {
            $this->setAttribute( 'value', $this->value );
        }
    }

    public function __construct( $args = [] )
    {
        parent::__construct( $args );
        $this->type = 'datetime';
        $this->attributes->setAttribute( 'type', 'datetime' );
    }
}