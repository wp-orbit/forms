<?php
namespace WPOrbit\Forms\Contexts;

/**
 * The post meta context relies on the post ID passed to the WordPress callbacks.
 *
 * Class PostMetaContext
 * @package WPOrbit\Forms\Contexts
 */
class PostMetaContext extends FormContext
{
    /**
     * @param int $postId When the form is used in a post context (like a meta box), we can
     * preload the form field values.
     */
    public function load($postId)
    {
        // Set field values before rendering.
        foreach( $this->form->fields->all() as $field )
        {
            switch( $field->getType() )
            {
                case 'html':
                    break;

                case 'checkbox-boolean':
                    $meta = get_post_meta( $postId, $field->getName(), true );
                    if ( 'true' == $meta ) {
                        $field->setAttribute( 'checked', 'checked' );
                    }
                    break;

                case 'radio':
                    $meta = get_post_meta( $postId, $field->getName(), true );
                    $field->setCheckedValue( $meta );
                    break;

                case 'radio-boolean':
                    $meta = get_post_meta( $postId, $field->getName(), true );
                    $meta = 'true' == $meta ? 'true' : 'false';
                    $field->setCheckedValue( $meta );
                    break;

                default:
                    // Set the field value.
                    $field->setValue( get_post_meta( $postId, $field->getName(), true ) );
                    break;
            }
        }
    }

    /**
     * Save post meta.
     * @param $postId
     * @throws \Exception
     */
    public function save($postId)
    {
        // Do nothing if not saving...
        if ( ! isset( $_POST ) || empty( $_POST ) )
        {
            return;
        }

        // Get form fields.
        $fields = $this->form->getSaveFields();

        if ( ! isset( $_POST[ $fields['nonceKey'] ] ) )
        {
            throw new \Exception( "No security token supplied." );
        }

        if ( ! wp_verify_nonce( $_POST[ $fields['nonceKey'] ], $fields['nonceAction'] ) )
        {
            throw new \Exception( "Invalid security token supplied." );
        }

        foreach( $this->form->fields->all() as $field )
        {
            switch ( $field->getType() )
            {
                case 'checkbox-boolean':
                    if ( isset( $_POST[ $field->getName() ] ) && 'true' == $_POST[ $field->getName() ] ) {
                        update_post_meta( $postId, $field->getName(), 'true' );
                    } else {
                        update_post_meta( $postId, $field->getName(), 'false' );
                    }
                    break;

                default:
                    if ( isset( $_POST[$field->getName()] ) ) {
                        update_post_meta( $postId, $field->getName(), $_POST[$field->getName()] );
                    }
                    break;
            }
        }
    }
}