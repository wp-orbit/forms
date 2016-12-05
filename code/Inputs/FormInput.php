<?php
namespace WPOrbit\Forms\Inputs;

/**
 * An abstract form input class.
 *
 * Class FormInput
 * @package WPOrbit\Forms\Inputs
 */
abstract class FormInput
{
    /**
     * @var string The HTML tag for this form element.
     */
    protected $tag = 'input';

    /**
     * @var string Form input type
     */
    protected $type;
    /**
     * @var string Label content.
     */
    protected $label;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string The key used to identify this form field.
     */
    protected $fieldId;

    /**
     * @var FormInputAttributes
     */
    protected $attributes;

    /**
     * @return bool|null
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setAttribute($key, $value)
    {
        $this->attributes->setAttribute( $key, $value );
    }

    public function getAttribute($key)
    {
        return $this->attributes->getAttribute( $key );
    }

    /**
     * @throws \Exception
     */
    protected function validateInput()
    {
        if ( null == $this->fieldId ) {
            throw new \Exception( 'No $fieldId is defined in class ' . static::class );
        }
    }

    /**
     * FormInput constructor.
     * @param array $args Fields: 'id', 'label', 'value', 'class'
     */
    public function __construct( $args = [] )
    {
        // Instantiate form attributes.
        $this->attributes = new FormInputAttributes;

        // Set the label.
        if ( isset( $args['label'] ) )
        {
            // Assign the label.
            $this->label = $args['label'];
        }

        // Set the ID.
        if ( isset( $args['id'] ) )
        {
            // Set the input key internally.
            $this->fieldId = $args['id'];
        }

        // Set the value if supplied.
        if ( isset( $args['value'] ) )
        {
            $this->value = $args['value'];
        }

        // Set the class if supplied.
        if ( isset( $args['class'] ) ) {
            $this->setAttribute( 'class', $args['class'] );
        }
    }

    protected function prepareAttributes()
    {
        $this->setAttribute( 'id', $this->fieldId );
        $this->setAttribute( 'name', $this->fieldId );
    }

    /**
     * @param array $exclude
     * @return string
     */
    protected function getAttributes( $exclude = [] )
    {
        $this->prepareAttributes();
        return $this->attributes->render( $exclude );
    }

    /**
     * @return string Get input element HTML.
     */
    public function render()
    {
        // Verify the input field is valid.
        $this->validateInput();

        // Return the rendered HTML.
        return "<{$this->tag} {$this->getAttributes()}/>";
    }
}