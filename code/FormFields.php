<?php
namespace WPOrbit\Forms;

use WPOrbit\Forms\Inputs\FormInput;
use WPOrbit\Forms\Inputs\HtmlInput;

class FormFields
{
    /**
     * @var FormInput[]
     */
    public $fields = [];

    /**
     * @return FormInput[]
     */
    public function all()
    {
        return $this->fields;
    }

    public function addField( FormInput $formInput )
    {
        // Add the form input field.
        $this->fields[] = $formInput;

        return $this;
    }

    public function addHtml( $content = '' )
    {
        $html = new HtmlInput([
            'content' => $content
        ]);

        $this->fields[] = $html;

        return $this;
    }
}