<?php
namespace WPOrbit\Forms\Inputs;

class RadioBooleanInput extends FormInput
{
    public $options = [
        'true' => 'Yes',
        'false' => 'No'
    ];

    public function __construct( $args = [] )
    {
        parent::__construct( $args );
        $this->type = 'radio-boolean';
        $this->setAttribute( 'type', 'radio' );
    }
}