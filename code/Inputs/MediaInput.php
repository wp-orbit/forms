<?php
namespace WPOrbit\Forms\Inputs;

class MediaInput extends FormInput
{
    // Define the type key.
    protected $type = 'media-input';

    /**
     * @var string The image preview size (ie, 'thumbnail', 'medium', 'full').
     */
    protected $previewSize = 'medium';

    /**
     * @var string WP Media frame select image text.
     */
    protected $frameButtonText = 'Select Media';

    /**
     * @var string WP Media frame modal title.
     */
    protected $frameTitle = 'Select Media';

    /**
     * Renders the preview image.
     * @return bool
     */
    protected function renderPreviewImage()
    {
        // Get the image ID (loaded via context).
        $imageId = $this->getValue();

        // If there is no image id, don't render anything.
        if ( ! $imageId || '' == $imageId ) {
            return false;
        }

        // Get the image URL.
        $imageUrl = wp_get_attachment_image_src( $imageId, $this->previewSize )[0];
        ?>
        <img src="<?= $imageUrl; ?>">
        <?php
    }

    /**
     * @return string Get input element HTML.
     */
    public function render()
    {
        // Verify the input field is valid.
        $this->validateInput();

        // Render the input.
        ob_start();
        ?>
        <!-- Image Preview -->
        <div id="<?= $this->getName(); ?>-preview-container">
            <?php $this->renderPreviewImage(); ?>
        </div>

        <!-- Form field -->
        <input type="hidden" value="<?= $this->getValue(); ?>" id="<?= $this->getName(); ?>" name="<?= $this->getName(); ?>">

        <button id="<?= $this->getName(); ?>-remove-image" type="button" class="button">Remove Media</button>
        <button id="<?= $this->getName(); ?>-select-image" type="button" class="button button-primary">Select Media</button>
        <?php
        return ob_get_clean();
    }

    protected function script()
    {
        ?>
        <script>
            (function($) {
                $(document).ready(function() {

                    var input = $('#<?= $this->getName(); ?>'),
                        selectButton = $('#<?= $this->getName(); ?>-select-image'),
                        removeButton = $('#<?= $this->getName(); ?>-remove-image'),
                        previewContainer = $('#<?= $this->getName(); ?>-preview-container');

                    var frame = new wp.media.view.MediaFrame.Select({
                        title: '<?= $this->frameTitle; ?>',
                        multiple: true,
                        library: {
                            order: 'ASC',
                            orderby: 'title',
                            type: 'image',
                            search: null,
                            uploadedTo: null
                        },
                        button: {
                            text: '<?= $this->frameButtonText; ?>'
                        }
                    });

                    // Open wp.media.
                    selectButton.on( 'click', function(e) {
                        frame.open();
                    });

                    removeButton.on('click', function(e) {
                        // Update the preview.
                        previewContainer.html( '<p>Choose an image.</p>' );
                        // Update the input value.
                        input.val('');
                        input.attr('value', '');
                        // Hide the remove button.
                        $(this).hide();
                    });

                    // Select callback.
                    frame.on( 'select', function() {
                        var selectionCollection = frame.state().get('selection'),
                            image = selectionCollection.models[0],
                            id = image.id,
                            url = 'undefined' == typeof(image.attributes.sizes.medium) ?
                                image.attributes.sizes.full.url : image.attributes.sizes.medium.url;

                        // Update the preview.
                        previewContainer.html( '<img src="' + url + '">' );

                        // Update the input value.
                        input.val(id);
                        input.attr('value', id);

                        // Show the remove button.
                        removeButton.fadeIn();
                    });

                    frame.state();
                    frame.lastState();
                });
            })(jQuery);
        </script>
        <?php
    }

    /**
     * MediaInput constructor.
     * @param array $args
     */
    public function __construct(array $args = [])
    {
        parent::__construct($args);

        $this->setAttribute( 'type', 'hidden' );

        add_action( 'admin_footer', function() {
            $this->script();
        });

        add_action( 'wp_footer', function() {
            $this->script();
        });
    }
}