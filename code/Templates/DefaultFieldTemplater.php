<?php
namespace WPOrbit\Forms\Templates;

/**
 * Only renders the form fields. Useful for meta boxes, etc.
 *
 * Class DefaultFieldTemplater
 * @package WPOrbit\Forms\Templates
 */
class DefaultFieldTemplater extends FormTemplater
{
    public function renderForm()
    {
        echo $this->renderItems();
        echo $this->renderToken();
    }
}