<?php
namespace WPOrbit\Forms\Inputs;

class ColorPickerInput extends FormInput
{
    protected function script()
    {
        wp_enqueue_script('iris');
        ?>
        <script>
            (function($) {
                $(document).ready(function()
                {
                    $('#<?= $this->getName(); ?>').iris({
                        change: function(event, ui) {
                            $('#<?= $this->getName(); ?>-preview').css( 'background-color', ui.color.toString());
                            $('#<?= $this->getName(); ?>-hide-preview').css('display', 'inline-block');
                        }
                    });

                    $('#<?= $this->getName(); ?>-preview').css({
                        width: '30px',
                        height: '29px',
                        'background-color': '<?= $this->getValue(); ?>'
                    });

                    $('#<?= $this->getName(); ?>-preview').on( 'click', function(e) {
                        $('#<?= $this->getName(); ?>-hide-preview').fadeIn();
                        $('#<?= $this->getName(); ?>').iris('show');
                    });

                    $('#<?= $this->getName(); ?>').on( 'click', function(e) {
                        $('#<?= $this->getName(); ?>-hide-preview').fadeIn();
                        $('#<?= $this->getName(); ?>').iris('show');
                    });

                    $('#<?= $this->getName(); ?>-hide-preview').on( 'click', function(e) {
                        $('#<?= $this->getName(); ?>').iris('hide');
                        $(this).hide();
                    });
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