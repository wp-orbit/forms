<?php
namespace WPOrbit\Forms\Inputs;

class ColorPickerInput extends FormInput
{
    protected function script()
    {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        ?>
        <script>
            (function($) {
                $(document).ready(function() {
                    $('#<?= $this->getName(); ?>').wpColorPicker();
                });
            })(jQuery);
        </script>
        <?php
    }

    public function __construct(array $args)
    {
        parent::__construct($args);

        // Register javascript.
        $this->type = 'color-picker';

        $this->setAttribute( 'class', 'widefat' );

        add_action( 'admin_footer', function() {
            $this->script();
        });

        add_action( 'wp_footer', function() {
            $this->script();
        });
    }
}