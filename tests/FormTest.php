<?php
class FormTest extends \WPOrbit\Tests\TestCase
{
    /**
     * @var \WPOrbit\Forms\Form
     */
    public $form;

    public function setUp()
    {
        parent::setUp();

        // Make the form.
        $this->form = new \WPOrbit\Forms\Form( 'test-form' );

        // Optionally specify the templater class.
        $this->form->setTemplater( \WPOrbit\Forms\Templates\DefaultFieldTemplater::class );

        // Add fields.
        $this->form->fields->addField(
            new \WPOrbit\Forms\Inputs\TextInput([
                'id' => 'test',
                'label' => '123'
            ]
        ));

        $this->form->fields->addField(
            new \WPOrbit\Forms\Inputs\ButtonInput([
                'id' => 'example-button',
                'value' => 'What?'
            ]
        ));
    }

    public function testCanRenderFormFields()
    {
        dump( $this->form->render() );
    }

    public function testCanGetSaveData()
    {
        dump( $this->form->getSaveFields() );
    }
}