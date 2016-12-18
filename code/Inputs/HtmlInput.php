<?php
namespace WPOrbit\Forms\Inputs;

/**
 * Insert arbitrary HTML into the form container.
 *
 * Has a wrapper built into FormFields class.
 *
 * Class HtmlInput
 * @package WPOrbit\Forms\Inputs
 * @see FormFields;
 */
class HtmlInput extends FormInput
{
    /**
     * HtmlBlock constructor.
     * @param array $args Fields: 'content'
     */
    public function __construct(array $args)
    {
        parent::__construct($args);

        // Set type to 'html';
        $this->type = 'html';

        if ( isset( $args['content'] ) ) {
            $this->setValue( $args['content'] );
        }
    }
}