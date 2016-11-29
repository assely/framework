<?php

namespace Assely\Foundation\Providers;

use Assely\Config\FrameworkConfig;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\Finder;

class FoundationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap framework services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(ASSELY_FRAMEWORK_DIR.'resources/views', 'Assely');

        $this->registerAssets();

        $this->registerJSVariables();
    }

    /**
     * Register any app services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfigs();
    }

    /**
     * Register framework configs.
     *
     * @return void
     */
    private function registerConfigs()
    {
        $this->app->singleton('config.framework', function () {
            return new FrameworkConfig($this->getConfigs());
        });

        $this->app->alias('config.framework', FrameworkConfig::class);
    }

    /**
     * Get configs values from files.
     *
     * @return mixed
     */
    public function getConfigs()
    {
        $configs = [];

        foreach ($this->getConfigFiles() as $file) {
            $name = basename($file->getFilename(), '.php');

            $values = require $file->getRealPath();

            $configs[$name] = $values;
        }

        return $configs;
    }

    /**
     * Gets all config files.
     *
     * @return array
     */
    public function getConfigFiles()
    {
        return Finder::create()
            ->files()
            ->in(ASSELY_FRAMEWORK_DIR.'config')
            ->name('*.php');
    }

    /**
     * Register framework assets.
     *
     * @return void
     */
    private function registerAssets()
    {
        $this->app['asset.factory']->add('assely-styles', [
            'path' => ASSELY_FRAMEWORK_URI.'public/css/assely.css',
        ])->area('admin');

        $this->app['asset.factory']->add('parsleyjs', [
            'path' => ASSELY_FRAMEWORK_URI.'public/js/vendors/parsley.js',
            'placement' => 'head',
        ])->area('admin');

        $this->app['asset.factory']->add('vue', [
            'path' => ASSELY_FRAMEWORK_URI.'public/js/vendors/vue.js',
            'placement' => 'head',
        ])->area('admin');

        $this->app['asset.factory']->add('assely-components', [
            'path' => ASSELY_FRAMEWORK_URI.'public/js/assely-components.js',
            'placement' => 'head',
            'dependences' => ['jquery', 'underscore', 'vue', 'parsleyjs'],
        ])->localize('Assely', [$this, 'localizationData'])->area('admin');
    }

    /**
     * Prints Assely javascript global variable.
     *
     * @return void
     */
    public function registerJSVariables()
    {
        $object = json_encode($this->localizationData());

        $this->app['hook.factory']->action('wp_head', function () use ($object) {
            echo "<script>var Assely = {$object};</script>";
        })->dispatch();
    }

    /**
     * Provides localization data for framework scripts.
     *
     * @return array
     */
    public function localizationData()
    {
        return [
            'locale' => get_locale(),
            'ajax' => [
                'url' => admin_url('admin-ajax.php'),
                'nonce' => $this->app['nonce.factory']->create('assely-ajax'),
            ],
        ];
    }
}
