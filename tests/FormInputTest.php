<?php
class FormInputTest extends \WPOrbit\Tests\TestCase
{
    /**
     * @var \WPOrbit\Forms\Inputs\TextInput
     */
    public $textInput;

    /**
     * @var \WPOrbit\Forms\Inputs\TextAreaInput
     */
    public $textAreaInput;

    public function setUp()
    {
        $this->textInput = new \WPOrbit\Forms\Inputs\TextInput([
            'id' => 'test_input',
            'label' => 'Test Input',
            'value' => 'cool test data'
        ]);

        $this->textAreaInput = new \WPOrbit\Forms\Inputs\TextAreaInput([
            'id' => 'test_text_area_input',
            'label' => 'My Text Area',
            'value' => 'Some content',
            'class' => 'widefat'
        ]);
    }

    public function testCanMakeFormInput()
    {
        dump( $this->textInput->render() );
        dump( $this->textAreaInput->render() );
    }
}