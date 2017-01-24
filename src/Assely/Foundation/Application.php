<?php

namespace Assely\Foundation;

use Assely\Config\ApplicationConfig;
use Assely\Foundation\AliasLoader;
use Assely\Routing\RoutingServiceProvider;
use Illuminate\Container\Container;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\Finder;

class Application extends Container
{
    /**
     * @var mixed
     */
    protected $booted;

    /**
     * @var array
     */
    protected $serviceProviders = [];

    /**
     * @var array
     */
    protected $loadedProviders = [];

    /**
     * Create a new Illuminate application instance.
     *
     * @param  string|null  $basePath
     * @return void
     */
    public function __construct($basePath = null)
    {
        if ($basePath) {
            $this->setBasePath($basePath);
        }

        $this->registerBaseBindings();
        $this->registerCoreContainerAliases();
        $this->registerApplicationConfig();
        $this->registerBaseFacades();
        $this->registerServiceProviders();
    }

    /**
     * Set the base path for the application.
     *
     * @param  string  $basePath
     * @return $this
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');
        $this->bindPathsInContainer();

        return $this;
    }

    /**
     * Bind all of the application paths in the container.
     *
     * @return void
     */
    protected function bindPathsInContainer()
    {
        $this->instance('path', $this->path());
        $this->instance('path.base', $this->basePath());
        $this->instance('path.lang', $this->langPath());
        $this->instance('path.config', $this->configPath());
        $this->instance('path.public', $this->publicPath());
        $this->instance('path.storage', $this->storagePath());
        $this->instance('path.resources', $this->resourcePath());
        $this->instance('path.bootstrap', $this->bootstrapPath());
    }

    /**
     * Get the path to the application "app" directory.
     *
     * @return string
     */
    public function path()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'app';
    }

    /**
     * Get the base path of the Laravel installation.
     *
     * @return string
     */
    public function basePath()
    {
        return $this->basePath;
    }

    /**
     * Get the path to the bootstrap directory.
     *
     * @return string
     */
    public function bootstrapPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'bootstrap';
    }

    /**
     * Get the path to the application configuration files.
     *
     * @return string
     */
    public function configPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'config';
    }

    /**
     * Get the path to the language files.
     *
     * @return string
     */
    public function langPath()
    {
        return $this->resourcePath() . DIRECTORY_SEPARATOR . 'lang';
    }

    /**
     * Get the path to the public / web directory.
     *
     * @return string
     */
    public function publicPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'public';
    }

    /**
     * Get the path to the storage directory.
     *
     * @return string
     */
    public function storagePath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'storage';
    }

    /**
     * Get the path to the resources directory.
     *
     * @return string
     */
    public function resourcePath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'resources';
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings()
    {
        static::setInstance($this);

        $this->instance('app', $this);

        $this->instance(Container::class, $this);
    }

    /**
     * Load application configured configs.
     *
     * @return void
     */
    public function registerApplicationConfig()
    {
        $this->singleton(ApplicationConfig::class, function () {
            return new ApplicationConfig($this->getConfigFiles());
        });

        $this->alias(ApplicationConfig::class, 'config');
    }

    /**
     * Gets all config files.
     *
     * @return array
     */
    public function getConfigFiles($configs = [])
    {
        $finder = $this->make(Finder::class)
            ->files()
            ->in($this->configPath())
            ->name('*.php');

        foreach ($finder as $file) {
            $configs[basename($file->getFilename(), '.php')] = require $file->getRealPath();
        }

        return $configs;
    }

    /**
     * Register the core class aliases in the container.
     *
     * @return void
     */
    public function registerCoreContainerAliases()
    {
        $aliases = [
            'app' => [
                'Assely\Foundation\Application',
                'Illuminate\Contracts\Container\Container',
                'Illuminate\Contracts\Foundation\Application',
            ],
            'blade.compiler' => [
                'Illuminate\View\Compilers\BladeCompiler',
            ],
            'view' => [
                'Illuminate\View\Factory',
                'Illuminate\Contracts\View\Factory',
            ],
        ];

        foreach ($aliases as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->alias($key, $alias);
            }
        }
    }

    /**
     * Register Base Facades.
     *
     * @return void
     */
    private function registerBaseFacades()
    {
        Facade::clearResolvedInstances();

        Facade::setFacadeApplication($this);

        AliasLoader::getInstance($this->make('config')->get('app.aliases'))->register();
    }

    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerServiceProviders()
    {
        $this->register(new EventServiceProvider($this));
        $this->register(new RoutingServiceProvider($this));

        foreach ($this->make('config')->get('app.providers') as $provider) {
            $this->register($provider);
        }
    }

    /**
     * @param $provider
     * @param array $options
     * @param $force
     * @return null
     */
    public function register($provider, array $options = [], $force = false)
    {
        if (($registered = $this->getProvider($provider)) && ! $force) {
            return $registered;
        }

        // If the given "provider" is a string, we will resolve it, passing in the
        // application instance automatically for the developer. This is simply
        // a more convenient way of specifying your service provider classes.
        if (is_string($provider)) {
            $provider = $this->resolveProviderClass($provider);
        }

        $provider->register();

        // Once we have registered the service we will iterate through the options
        // and set each of them on the application so they will be available on
        // the actual loading of the service objects and for developer usage.
        foreach ($options as $key => $value) {
            $this[$key] = $value;
        }

        $this->markAsRegistered($provider);

        // If the application has already booted, we will call this boot method on
        // the provider class so it has an opportunity to do its boot logic and
        // will be ready for any usage by the developer's application logics.
        if ($this->booted) {
            $this->bootProvider($provider);
        }

        return $provider;
    }

    /**
     * Get the registered service provider instance if it exists.
     *
     * @param \Illuminate\Support\ServiceProvider|string $provider
     *
     * @return \Illuminate\Support\ServiceProvider|null
     */
    public function getProvider($provider)
    {
        $name = is_string($provider) ? $provider : get_class($provider);

        return Arr::first($this->serviceProviders, function ($key, $value) use ($name) {
            return $value instanceof $name;
        });
    }

    /**
     * Resolve a service provider instance from the class name.
     *
     * @param string $provider
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function resolveProviderClass($provider)
    {
        return new $provider($this);
    }

    /**
     * Mark the given provider as registered.
     *
     * @param  \Illuminate\Support\ServiceProvider  $provider
     * @return void
     */
    protected function markAsRegistered($provider)
    {
        $this->serviceProviders[] = $provider;

        $this->loadedProviders[get_class($provider)] = true;
    }

    /**
     * Boot the given service provider.
     *
     * @param \Illuminate\Support\ServiceProvider $provider
     *
     * @return mixed
     */
    protected function bootProvider(ServiceProvider $provider)
    {
        if (method_exists($provider, 'boot')) {
            return $this->call([$provider, 'boot']);
        }
    }

    /**
     * Boot the application's service providers.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->booted) {
            return;
        }

        array_walk($this->serviceProviders, function ($p) {
            $this->bootProvider($p);
        });

        $this->booted = true;
    }
}
