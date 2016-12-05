<?php
namespace WPOrbit\Forms\Templates;

use WPOrbit\Forms\Form;
use WPOrbit\Forms\Inputs\FormInput;

abstract class FormTemplater
{
    /**
     * @var Form
     */
    protected $form;

    public function filterFieldClass( FormInput $field, $filter )
    {
        $css = $field->getAttribute( 'class' );

        $field->setAttribute( 'class', $filter($css) );
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
        foreach( $field->getOptions() as $value => $label )
        {
            $elementId =  $field->getName() . '-' . $i;
            $isChecked = $checked == $value ? ' checked="checked"' : '';
            ?>
            <label for="<?= $elementId; ?>">
                <input type="radio" id="<?= $elementId; ?>" name="<?= $field->getName(); ?>" value="<?= $value; ?>"<?= $isChecked; ?>>
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


    public function renderField( FormInput $field )
    {
        // Declare the output string.
        $output = '';

        // Open the form field.
        $output .= "\t" . '<div class="form-field">' . "\n";

        switch( $field->getType() )
        {
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

            default:
                $output .= $this->_renderDefaultField( $field );
                break;
        }

        $output .= "\t" . '</div>' . "\n";

        return $output;
    }

    public function renderItems()
    {
        $output = [];

        // Loop through this form's fields.
        foreach( $this->form->fields->all() as $field )
        {
            /** @var $field FormInput */
            $output[] = $this->renderField( $field );
        }

        return implode( '', $output );
    }

    public function getFormHeader()
    {
        $methodString = $this->form->method ? ' method="' . $this->form->method . '"' : '';;
        $actionString = $this->form->action ? ' action="' . $this->form->action . '"' : '';
        $idString = $this->form->id ? ' id="' . $this->form->id . '"' : '';

        $output =
            "<form" .
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