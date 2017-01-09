<?php
namespace WPOrbit\Forms\Inputs;

class SelectPostsInput extends FormInput
{
    public function __construct( $args = [] )
    {
        // Parent constructor.
        parent::__construct( $args );

        // Option type.
        $this->type = 'select';

        // Set attribute.
        $this->setAttribute( 'type', 'select' );

        // Post type.
        $postType = isset( $args[ 'postType' ] ) ? $args[ 'postType' ] : 'post';

        // Query posts.
        $query = new \WP_Query( [
            'post_type' => $postType,
            'nopaging'  => true,
            'orderby'   => 'title'
        ] );

        // Declare options.
        $options = [];

        // Loop through queried posts.
        foreach ( $query->posts as $post )
        {
            // Push to options array.
            $options[] = [
                'value' => $post->ID,
                'label' => $post->post_title
            ];
        }

        // Set options.
        $this->setOptions( $options );
    }

    /**
     * @return string
     */
    public function render()
    {
        // Verify the input field is valid.
        $this->validateInput();

        // Set 'widefat'.
        $this->setAttribute( 'class', 'widefat' );

        // Return the rendered HTML.
        ob_start();
        ?>
        <select <?= $this->getAttributes(); ?>>
            <?php foreach( $this->options as $option ) : ?>
            <option value="<?= $option['value']; ?>"><?= $option->value; ?></option>
            <?php endforeach; ?>
        </select>
        <?php

        return ob_get_clean();
    }
}