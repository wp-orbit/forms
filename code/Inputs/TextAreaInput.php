<?php
namespace WPOrbit\Forms\Inputs;

/**
 * A basic text input field.
 *
 * Class TextAreaInput
 * @package WPOrbit\Forms\Inputs
 */
class TextAreaInput extends FormInput
{
    /**
     * @var string Text area content.
     */
    protected $content = '';

    public function __construct( $args = [] )
    {
        parent::__construct( $args );
        $this->tag = 'textarea';
        $this->type = 'textarea';
    }

    /**
     * @return string Get HTML.
     */
    public function render()
    {
        // Verify the input field is valid.
        $this->validateInput();

        // Get attributes.
        $attributes = $this->getAttributes(['value']);

        // Get content.
        $content = esc_textarea( $this->getValue() );

        return "<textarea {$attributes}/>{$content}</textarea>";
    }
}