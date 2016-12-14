<?php

namespace Assely\Field;

use Assely\Hook\HookFactory;
use Assely\Asset\AssetFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;

class FieldManager
{
    /**
     * Construct field manager.
     *
     * @param \Assely\Hook\HookFactory $hook
     * @param \Assely\Asset\AssetFactory $asset
     * @param \Assely\View\ViewFactory $view
     * @param \Assely\Field\FieldValidator $validator
     */
    public function __construct(
        HookFactory $hook,
        AssetFactory $asset,
        ViewFactory $view,
        FieldValidator $validator
    ) {
        $this->hook = $hook;
        $this->asset = $asset;
        $this->view = $view;
        $this->validator = $validator;
    }

    /**
     * Bootstrap field manager.
     *
     * @param \Assely\Field\Field $field
     *
     * @return void
     */
    public function boot($field)
    {
        $this->field = $field;

        $this->dispatchAssets();
    }

    /**
     * Dispach field assets.
     *
     * @return void
     */
    public function dispatchAssets()
    {
        if (method_exists($this->field, 'assets')) {
            $this->field->assets($this->asset);
        }
    }

    /**
     * Dispach field template.
     *
     * @return void
     */
    public function dispatchTemplate()
    {
        $this->hook->action(
            'admin_footer',
            [$this, 'renderTemplate']
        )->dispatch();
    }

    /**
     * Render field template.
     *
     * @return void
     */
    public function renderTemplate()
    {
        echo $this->field->template($this->view);
    }

    /**
     * Render field preview.
     *
     * @param mixed $value
     *
     * @return void
     */
    public function renderPreview($value)
    {
        echo $this->field->preview($this->view, $value);
    }

    /**
     * Gets field validator.
     *
     * @return \Assely\Field\FieldValidator
     */
    public function getValidator()
    {
        return $this->validator;
    }
}
