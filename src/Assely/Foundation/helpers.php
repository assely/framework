<?php

namespace Assely\Helpers;

use Assely\Foundation\Application;

if (! function_exists('app')) {
    /**
     * Get application container.
     *
     * @param  string $make
     *
     * @return mixed|\Assely\Foundation\Application
     */
    function app($make = null)
    {
        if (null === $make) {
            return Application::getInstance();
        }

        return Application::getInstance()->make($make);
    }
}

if (! function_exists('asset')) {
    /**
     * Gets asset.
     *
     * @param  string $slug
     *
     * @return \Assely\Asset\Asset
     */
    function asset($slug)
    {
        return app('collection.assets')->get($slug);
    }
}

if (! function_exists('nonce')) {
    /**
     * Get nonces factory.
     *
     * @return \Assely\Nonce\NonceFactory
     */
    function nonce()
    {
        return app('nonce');
    }
}

if (! function_exists('config')) {
    /**
     * Get theme configs.
     *
     * @param  string $name
     *
     * @return array
     */
    function config($name)
    {
        return app('config')->get($name);
    }
}

if (! function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param  string  $path
     *
     * @return string
     */
    function public_path($path)
    {
        return app('config')->get('app.path').DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$path;
    }
}

if (! function_exists('public_dir')) {
    /**
     * Get the dir to the public folder.
     *
     * @param  string  $dir
     *
     * @return string
     */
    function public_dir($dir)
    {
        return app('config')->get('app.directory').DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$dir;
    }
}

if (! function_exists('view')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string  $view
     * @param  array   $data
     * @param  array   $mergeData
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function view($view = null, $data = [], $mergeData = [])
    {
        $factory = app('view');

        if (func_num_args() === 0) {
            return $factory;
        }

        echo $factory->make($view, $data, $mergeData)->render();
    }
}

if (! function_exists('mix')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param  string  $path
     * @return \Illuminate\Support\HtmlString
     *
     * @throws \Exception
     */
    function mix($path)
    {
        static $manifest;
        static $shouldHotReload;

        if (! $manifest) {
            if (! file_exists($manifestPath = public_dir('mix-manifest.json'))) {
                throw new \Exception('The Mix manifest does not exist.');
            }

            $manifest = json_decode(file_get_contents($manifestPath), true);
        }

        if (! starts_with($path, '/')) {
            $path = "/{$path}";
        }

        if (! array_key_exists($path, $manifest)) {
            throw new \Exception(
                "Unable to locate Mix file: {$path}. Please check your ".
                'webpack.mix.js output paths and try again.'
            );
        }

        return $shouldHotReload = file_exists(public_path('hot'))
                ? new \Illuminate\Support\HtmlString("http://localhost:8080{$manifest[$path]}")
                : new \Illuminate\Support\HtmlString($manifest[$path]);
    }
}
