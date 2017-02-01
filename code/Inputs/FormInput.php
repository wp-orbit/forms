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
     * @var array
     */
    protected $options = [];

    /**
     * @var string Container column width.
     */
    protected $columns = 'small-12';

    /**
     * @return int|string
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $options
     */
    public function setOptions( array $options )
    {
        $this->options = $options;
    }

    protected $checkedValue;

    public function getCheckedValue()
    {
        return $this->checkedValue;
    }

    public function setCheckedValue($value)
    {
        $this->checkedValue = $value;
    }

    /**
     * @return bool|null
     */
    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
        $this->setAttribute( 'value', $value );
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getName()
    {
        return $this->fieldId;
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

    public function removeAttribute($key)
    {
        $this->attributes->unsetAttribute( $key );
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

        if ( isset( $args['columns'] ) ) {
            $this->columns = $args['columns'];
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
        // Prepare attributes.
        $this->prepareAttributes();

        // Return the rendered attributes string.
        return $this->attributes->render( $exclude );
    }

    protected function beforeRender()
    {

    }

    protected function afterRender()
    {

    }

    /**
     * @return string Get input element HTML.
     */
    public function render()
    {
        // Verify the input field is valid.
        $this->validateInput();

        // Set 'widefat'.
        $this->setAttribute( 'class', 'widefat' );

        // Return the rendered HTML.
        return
            $this->beforeRender() .
            "<{$this->tag} {$this->getAttributes()}/>" .
            $this->afterRender();
    }
}