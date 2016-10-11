<?php

namespace Assely\Singularity;

use Assely\Config\ApplicationConfig;
use Assely\Config\FrameworkConfig;
use Illuminate\Support\ServiceProvider;

class SingularityServiceProvider extends ServiceProvider
{
    /**
     * Base application singularities.
     *
     * @var array
     */
    protected $singularities = [];

    /**
     * Register singularity services.
     *
     * @return void
     */
    public function register()
    {
        // First, register all singularities defined
        // inside application configuration file.
        $this->registerConfiguredSingularities();

        // Second, register all additionally defined
        // singularities inside service provider.
        $this->registerAdditionalSingularities();
    }

    /**
     * Bootstrap singularity services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register configured singularites.
     *
     * @return void
     */
    public function registerConfiguredSingularities()
    {
        foreach ($this->getAllowedSingularityTypes() as $type) {
            $this->resolveSingularities($this->getSingularityType($type), $type);
        }
    }

    /**
     * Register additional singularities.
     *
     * @return void
     */
    public function registerAdditionalSingularities()
    {
        if (! empty($this->singularities)) {
            $this->resolveSingularities($this->singularities);
        }
    }

    /**
     * Return accepted by framework singularity types.
     *
     * @return array
     */
    public function getAllowedSingularityTypes()
    {
        $config = $this->app->make(FrameworkConfig::class);

        return $config->get('singularities.types');
    }

    /**
     * Get confugured in app singularity type.
     *
     * @param  string $type
     *
     * @return array
     */
    public function getSingularityType($type)
    {
        $config = $this->app->make(ApplicationConfig::class);

        return $config->get("singularities.{$type}");
    }

    /**
     * Register all singularity instances from the class name.
     *
     * @param  array $singularites
     *
     * @return void
     */
    public function resolveSingularities(array $singularites)
    {
        foreach ($singularites as $singularity) {
            $this->resolveSingularity($singularity);
        }
    }

    /**
     * Resolve singularity instance from the class name.
     *
     * @param  string $class
     *
     * @return void
     */
    public function resolveSingularity($singularity)
    {
        $class = $this->app->make($singularity);

        $this->app->instance($singularity, $class->make($this->app));
    }
}
