<?php
namespace WPOrbit\Forms\Inputs;

/**
 * Class RadioInputs
 * @package WPOrbit\Forms\Inputs
 */
class RadioInputs extends FormInput
{
    public function __construct( $args = [] )
    {
        parent::__construct( $args );
        $this->type = 'radio';
        $this->setAttribute( 'type', 'radio' );
        if ( isset( $args['options'] ) ) {
            $this->setOptions( $args['options'] );
        }
    }
}