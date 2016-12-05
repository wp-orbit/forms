<?php
namespace WPOrbit\Forms\Inputs;

/**
 * Form field HTML attributes.
 *
 * Class FormInputAttributes
 * @package WPOrbit\Forms\Inputs
 */
class FormInputAttributes
{
    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * Set an HTML attribute.
     * @param $key
     * @param $value
     */
    public function setAttribute( $key, $value )
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Return an attribute if defined.
     * @param $key
     * @return mixed
     */
    public function getAttribute( $key )
    {
        if ( isset( $this->attributes[$key] ) )
        {
            return $this->attributes[$key];
        }

        return null;
    }

    /**
     * Render the attributes string.
     * @param array $exclude Attributes to exclude from rendering.
     * @return string
     */
    public function render( $exclude = [] )
    {
        // Declare output array.
        $output = [];

        // Loop through attributes.
        foreach( $this->attributes as $key => $value )
        {
            // Ignore specified keys.
            if ( in_array( $key, $exclude ) ) {
                continue;
            }

            // Clean the attribute.
            $value = esc_attr( $value );

            //Push to array.
            $output[] = "{$key}=\"{$value}\"";
        }

        // Join by space.
        $string = implode( ' ', $output );

        // Trim spaces.
        $string = trim( $string );

        return $string;
    }
}