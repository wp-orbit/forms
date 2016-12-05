<?php
namespace WPOrbit\Forms\Templates;

class DefaultFormTemplater extends FormTemplater
{
    public function renderForm()
    {
        echo $this->getFormHeader();
        echo $this->renderItems();
        echo $this->getFormFooter();
    }
}