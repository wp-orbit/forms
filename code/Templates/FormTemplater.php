<?php
namespace WPOrbit\Forms\Templates;

use WPOrbit\Forms\Form;
use WPOrbit\Forms\Inputs\FormInput;
use WPOrbit\Forms\Inputs\MediaInput;

abstract class FormTemplater
{
    /**
     * @var Form
     */
    protected $form;

    public function filterFieldClass( FormInput $field, $filter )
    {
        $css = $field->getAttribute( 'class' );
        $field->setAttribute( 'class', $filter( $css ) );
    }

    protected function _renderCheckBox( FormInput $field )
    {
        ob_start();
        ?>
        <label for="<?= $field->getName(); ?>">
            <?= $field->render(); ?>
            <?= $field->getLabel(); ?>
        </label>
        <?php
        return ob_get_clean();
    }

    protected function _renderRadioGroup( FormInput $field )
    {
        // Get checked value.
        $checked = $field->getCheckedValue();

        ob_start();
        ?>
        <label>
            <?= $field->getLabel(); ?>
        </label><br>

        <?php
        $i = 1;
        foreach ( $field->getOptions() as $value => $label )
        {
            $elementId = $field->getName() . '-' . $i;
            $isChecked = $checked == $value ? ' checked="checked"' : '';
            ?>
            <label for="<?= $elementId; ?>">
                <input type="radio" id="<?= $elementId; ?>" name="<?= $field->getName(); ?>"
                       value="<?= $value; ?>"<?= $isChecked; ?>>
                <?= $label; ?>
            </label><br>
            <?php
            $i++;
        }

        return ob_get_clean();
    }

    protected function _renderDefaultField( FormInput $field )
    {
        ob_start();
        ?>
        <label for="<?= $field->getName(); ?>"><?= $field->getLabel(); ?></label><br>
        <?= $field->render(); ?>
        <?php
        return ob_get_clean();
    }

    protected function _renderColorPicker( FormInput $field )
    {
        ob_start();
        $field->setAttribute( 'style', 'width: 200px; position: relative; top: -10px;' );
        ?>
        <label for="<?= $field->getName(); ?>"><?= $field->getLabel(); ?></label><br>
        <div id="<?= $field->getName(); ?>-preview" style="display: inline-block;"></div>
        <?= $field->render(); ?>
        <button id="<?= $field->getName(); ?>-hide-preview" type="button" class="button" style="display: none;">Hide
            Preview
        </button>
        <?php
        return ob_get_clean();
    }

    public function _renderMediaInput( FormInput $field )
    {
        ob_start();
        ?>
        <label for="<?= $field->getName(); ?>"><?= $field->getLabel(); ?></label><br>
        <hr>
        <?= $field->render(); ?>
        <?php
        return ob_get_clean();
    }

    public function renderField( FormInput $field )
    {
        // Declare the output string.
        $output = '';

        // Open the form field.
        switch ( $field->getType() )
        {
            case 'color-picker':
                $output .= $this->_renderColorPicker( $field );
                break;

            // Render HTML.
            case 'html':
                $output .= $field->getValue();
                break;

            case 'checkbox-boolean':
                $output .= $this->_renderCheckBox( $field );
                break;

            case 'radio':
            case 'radio-boolean':
                $output .= $this->_renderRadioGroup( $field );
                break;

            case 'media-input':
            case 'multiple-media-input':
                $output .= $this->_renderMediaInput( $field );
                break;

            default:
                $output .= $this->_renderDefaultField( $field );
                break;
        }

        return $output;
    }

    public function renderBeforeField( FormInput $field )
    {
        // Do nothing for 'html' types.
        if ( 'html' == $field->getType() ) {
            return '';
        }

        // Column width.
        $width = $field->getColumns();

        if ( 'foundation' == $this->form->columnFramework ) {
            return "<div class='columns {$width}'>";
        }

        if ( 'bootstrap' == $this->form->columnFramework ) {
            return "<div class='{$width}'>";
        }

        return '<div>';
    }

    public function renderAfterField( FormInput $field )
    {
        // Do nothing for 'html' types.
        if ( 'html' == $field->getType() ) {
            return '';
        }

        return '</div>';
    }

    public function renderItems()
    {
        $output = [];

        // Loop through this form's fields.
        foreach ( $this->form->fields->all() as $field )
        {
            /** @var $field FormInput */
            $output[] = $this->renderBeforeField( $field );
            $output[] = $this->renderField( $field );
            $output[] = $this->renderAfterField( $field );
        }

        return implode( '', $output );
    }

    public function getFormHeader()
    {
        $methodString = $this->form->method ? ' method="' . $this->form->method . '"' : '';;
        $actionString = $this->form->action ? ' action="' . $this->form->action . '"' : '';
        $idString = $this->form->id ? ' id="' . $this->form->id . '"' : '';

        $output
            = "<form" .
              $idString .
              $actionString .
              $methodString .
              ">\n";

        return $output;
    }

    public function renderToken()
    {
        return "\t" . wp_nonce_field( 'form-' . $this->form->id, '_nonce_' . $this->form->id, true, false ) . "\n";
    }

    /**
     * Returns the token and closes the form.
     *
     * @return string
     */
    public function getFormFooter()
    {
        return $this->renderToken() . '</form>';
    }

    public function renderForm()
    {
        return $this->renderItems();
    }

    public function __construct( Form $form )
    {
        $this->form = $form;
    }
}