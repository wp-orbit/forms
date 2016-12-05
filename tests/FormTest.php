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
        $this->form = new \WPOrbit\Forms\Form( 'test-form' );


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

    public function testCanDoFormFields()
    {
        dump( $this->form->render() );
    }
}