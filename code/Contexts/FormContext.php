<?php
namespace WPOrbit\Forms\Contexts;

use WPOrbit\Forms\Form;

/**
 * A FormContext is used to help saving and loading form data; for example,
 * when working with PostMeta or Options.
 *
 * Class FormContext
 * @package WPOrbit\Forms\Contexts
 * @method save($data)
 * @method load($data)
 */
abstract class FormContext
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * FormContext constructor.
     * @param Form $form
     */
    public function __construct( Form $form )
    {
        $this->form = $form;
    }
}