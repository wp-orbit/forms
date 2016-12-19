<?php
namespace WPOrbit\Forms\Inputs;

/**
 * Class MultipleMediaInput
 * @package WPOrbit\Forms\Inputs
 */
class MultipleMediaInput extends FormInput
{
    // Define the type key.
    protected $type = 'multiple-media-input';

    /**
     * @var string The image preview size (ie, 'thumbnail', 'medium', 'full').
     */
    protected $previewSize = 'thumbnail';

    /**
     * @var string WP Media frame select image text.
     */
    protected $frameButtonText = 'Select Images';

    /**
     * @var string WP Media frame modal title.
     */
    protected $frameTitle = 'Select Images';

    /**
     * @return array|bool|null
     */
    protected function getAttachmentIds()
    {
        // Get the attachment IDs (loaded via context).
        $attachmentIds = $this->getValue();

        // If there are no attachment ids, return empty array.
        if ( ! $attachmentIds || '' == $attachmentIds ) {
            return [];
        }

        // Are there multiple attachments (search for presence of comma).
        $multipleAttachments = false !== strpos( $attachmentIds, ',' );

        // Prepare attachment IDs array.
        if ( $multipleAttachments ) {
            $attachmentIds = explode( ',', $attachmentIds );
        } else {
            $attachmentIds = [$attachmentIds];
        }

        // Return the attachment
        return $attachmentIds;
    }

    /**
     * Renders the preview image.
     * @return bool
     */
    protected function renderPreviewImages()
    {
        // Get attachment IDs.
        $imageIds = $this->getAttachmentIds();

        // Loop through image IDs.
        foreach( $imageIds as $imageId )
        {
            $imageUrl = wp_get_attachment_image_src( $imageId, $this->previewSize )[0];
            ?>
            <div class="image-item" data-id="<?= $imageId; ?>">
                <img src="<?= $imageUrl; ?>">
                <button type="button" class="button remove-media">Remove</button>
            </div>
            <?php
        }
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
            <?php $this->renderPreviewImages(); ?>
        </div>

        <!-- Form field -->
        <input type="hidden" value="<?= $this->getValue(); ?>" id="<?= $this->getName(); ?>" name="<?= $this->getName(); ?>">

        <button id="<?= $this->getName(); ?>-select-image" type="button" class="button button-primary">Add Media</button>
        <?php
        return ob_get_clean();
    }

    protected function script()
    {
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-draggable' );
        wp_enqueue_script( 'jquery-ui-droppable' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'underscore' );
        ?>
        <script>
            (function($) {
                $(document).ready(function() {

                    var input = $('#<?= $this->getName(); ?>'),
                        attachmentIds = <?= json_encode( $this->getAttachmentIds() ) ;?>,
                        selectButton = $('#<?= $this->getName(); ?>-select-image'),
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

                    // Get IDs from value="" attribute.
                    function getIds() {
                        var val = input.val().trim();
                        if ( '' == val ) {
                            return [];
                        }
                        return input.val().trim().split(',');
                    }

                    // Add an ID.
                    function addId(id) {
                        if ( '' == id ) {
                            return;
                        }
                        var ids = getIds();
                        ids.push(id);
                        input.val( ids.join(',') );
                    }

                    // Remove an ID.
                    function removeId(id) {
                        // Get IDs.
                        var ids = getIds();
                        // Remove the item.
                        $('#<?= $this->getName(); ?>-preview-container div[data-id="' + id + '"]').remove();
                        ids.splice( ids.indexOf( String( id ) ), 1 );
                        input.val( ids.join(',') );
                    }

                    // Add an image.
                    function addImage(image) {

                        // Get image URL.
                        var url = 'undefined' == typeof(image.attributes.sizes.thumbnail) ?
                                image.attributes.sizes.full.url : image.attributes.sizes.thumbnail.url;

                        // Set image string.
                        var imageString = '' +
                            '<div class="image-item" data-id="' + image.id + '">' +
                                '<img src="' + url + '">' +
                                '<button class="button remove-media">Remove</button>' +
                            '</div>';

                        // Append HTML.
                        previewContainer.append( imageString );
                    }

                    // Bind button callbacks.
                    function bindButtons() {
                        var removeButtons = $('#<?= $this->getName(); ?>-preview-container').find('.remove-media');
                        removeButtons.on( 'click', function(e) {
                            var button = $(e.target),
                                id = button.parent().data('id');
                            removeId( id );
                        });

                        $( "#<?= $this->getName(); ?>-preview-container" ).sortable({
                            update: function(event, ui) {
                                var order = [];
                                _.each( $( "#<?= $this->getName(); ?>-preview-container .image-item" ), function(item) {
                                    order.push( $(item).data('id') );
                                });
                                input.val( order.join(',') );
                            }
                        });
                        $( "#<?= $this->getName(); ?>-preview-container" ).disableSelection();
                    }

                    // Open wp.media.
                    selectButton.on( 'click', function(e) {
                        frame.open();
                    });

                    // Select callback.
                    frame.on( 'select', function() {
                        var selectionCollection = frame.state().get('selection'),
                            images = selectionCollection.models;

                        _.each( images, function(image) {
                            if ( -1 == getIds().indexOf( String( image.id ) ) ) {
                                addId( String( image.id ) );
                                addImage( image );
                            }
                        });

                        bindButtons();
                    });

                    // Bind buttons on load.
                    bindButtons();

                    // Preload frame state.
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
        parent::__construct( $args );

        $this->setAttribute( 'type', 'hidden' );

        add_action( 'admin_footer', function() {
            $this->script();
        });

        add_action( 'wp_footer', function() {
            $this->script();
        });
    }
}