<?php

namespace Assely\Asset;

use Assely\Hook\HookFactory;
use Assely\Config\FrameworkConfig;
use Assely\Support\Facades\Config;
use Assely\Config\ApplicationConfig;
use Assely\Support\Accessors\HasSlug;
use Illuminate\Filesystem\Filesystem;
use Assely\Support\Accessors\HasArguments;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class Asset
{
    use HasSlug, HasArguments;

    /**
     * Default asset arguments.
     *
     * @var array
     */
    private $defaults = [
        'path' => null,
        'area' => 'theme',
        'type' => false,
        'media' => 'screen',
        'placement' => 'footer',
        'version' => null,
        'localize' => false,
        'dependences' => [],
        'execution' => [],
    ];

    /**
     * Asset action type.
     *
     * @var string
     */
    private $action;

    /**
     * Construct asset.
     *
     * @param HookFactory $hook
     * @param array $settings
     * @param string $slug
     * @param array $arguments
     */
    public function __construct(
        Filesystem $filesystem,
        HookFactory $hook,
        FrameworkConfig $settings,
        ApplicationConfig $config
    ) {
        $this->filesystem = $filesystem;
        $this->hook = $hook;
        $this->settings = $settings;
        $this->config = $config;
    }

    /**
     * Dispach asset to action handler.
     *
     * @param  string $action
     *
     * @return self
     */
    public function dispatchTo($action)
    {
        $this->setAction($action);

        $this->hook->action(
            'init',
            [$this, 'hooks']
        )->dispatch();

        return $this;
    }

    /**
     * Register asset hooks.
     *
     * @return void
     */
    public function hooks()
    {
        $this->hook->action(
            $this->isAllowedArea($this->getArea()),
            [$this, 'handleAction']
        )->dispatch();

        if (! empty($this->getArgument('execution'))) {
            $this->hook->filter(
                'script_loader_tag',
                [$this, 'addExecutionTypes']
            )->dispatch();
        }
    }

    /**
     * Handle asset action. Take an action only
     * if asset area is on the allowed list.
     *
     * @return void
     */
    public function handleAction()
    {
        if ($this->getArea() === $this->getAllowedArea(current_filter())) {
            $this->{$this->getAction()}();
        }
    }

    /**
     * Register asset.
     *
     * @return self
     */
    public function register()
    {
        if (! $this->is('registered')) {
            call_user_func("wp_register_{$this->getType()}",
                $this->getSlug(),
                $this->getPath(),
                $this->getArgument('dependences'),
                $this->getArgument('version'),
                ($this->getPlacement() === 'footer') ? true : false
            );

            $this->localizeAsset();
        }

        return $this;
    }

    /**
     * Enqueue asset.
     *
     * @return self
     */
    public function enqueue()
    {
        if (! $this->is('enqueued')) {
            call_user_func("wp_enqueue_{$this->getType()}", $this->getSlug());
        }
    }

    /**
     * Deregister asset.
     *
     * @return self
     */
    public function deregister()
    {
        if ($this->is('registered')) {
            call_user_func(
                "wp_deregister_{$this->getType()}",
                $this->getSlug()
            );
        }
    }

    /**
     * Add asset.
     *
     * @return self
     */
    public function add()
    {
        return $this->register()->enqueue();
    }

    /**
     * Localize asset with data.
     *
     * @param  string   $var
     * @param  callable $callback
     *
     * @return self
     */
    public function localize($var, callable $callback)
    {
        $this->setArgument('localize', [
            'var' => $var,
            'callback' => $callback(),
        ]);

        return $this;
    }

    /**
     * Set async asset execution.
     *
     * @return self
     */
    public function async()
    {
        $this->setExecution(['async']);

        return $this;
    }

    /**
     * Set defered asset execution.
     *
     * @return self
     */
    public function defer()
    {
        $this->setExecution(['defer']);

        return $this;
    }

    /**
     * Set asset dependences.
     *
     * @param array $dependences
     *
     * @return self
     */
    public function dependences(array $dependences)
    {
        $this->setArgument('dependences', $dependences);

        return $this;
    }

    /**
     * Set asset area.
     *
     * @param  string $area
     *
     * @return self
     */
    public function area($area)
    {
        $this->setArgument('area', $area);

        return $this;
    }

    /**
     * Check asset state.
     *
     * @param  string  $state State name
     *
     * @return bool
     */
    public function is($state)
    {
        return wp_script_is($this->getSlug(), $state);
    }

    /**
     * Localize asset.
     *
     * @return self
     */
    private function localizeAsset()
    {
        if ($this->getArgument('localize')) {
            wp_localize_script(
                $this->getSlug(),
                $this->getArgument('localize')['var'],
                $this->getArgument('localize')['callback']
            );
        }

        return $this;
    }

    /**
     * Add specifed execution type to the asset html tag.
     *
     * @param string $tag
     * @param string $handler
     *
     * @return string
     */
    public function addExecutionTypes($tag, $handler)
    {
        if ($handler === $this->getSlug()) {
            $attributes = implode(' ', $this->getArgument('execution'));

            return str_replace(' src', " {$attributes} src", $tag);
        }

        return $tag;
    }

    /**
     * Sets asset execution types.
     *
     * @param array $types
     *
     * @return void
     */
    private function setExecution(array $types)
    {
        $this->setArgument('execution', array_merge(
            $this->getExecution(),
            $types
        ));
    }

    /**
     * Gets asset execution types.
     *
     * @param array $types
     *
     * @return void
     */
    public function getExecution()
    {
        return $this->getArgument('execution');
    }

    /**
     * Get asset path.
     *
     * @return string
     */
    public function getPath()
    {
        $path = $this->getArgument('path');

        // If path is absolute return it without modifications.
        if (
            strpos($path, 'http://') !== false
            || strpos($path, 'https://') !== false
            || substr($path, 0, 2) === '//'
        ) {
            return $path;
        }

        $filePath = $this->config->get('app.directory').DIRECTORY_SEPARATOR."public/{$path}";
        $fileUrl = $this->config->get('app.path').DIRECTORY_SEPARATOR."public/{$path}";

        if ($this->filesystem->exists($filePath)) {
            return $fileUrl;
        }

        throw new FileNotFoundException("File does not exist at path {$path}");
    }

    /**
     * Gets asset type.
     *
     * @return string
     */
    public function getType()
    {
        $type = $this->getArgument('type');
        $allowed = $this->getAllowedTypes();

        if ($type) {
            if (! $this->isAllowedType($type)) {
                throw new AssetException('Illegal Asset Type: Assets types can be only '.implode(', ', $allowed));
            }

            return $type;
        }

        $ext = pathinfo($this->getArgument('path'), PATHINFO_EXTENSION);

        return ($ext && $ext === 'js') ? $allowed[0] : $allowed[1];
    }

    /**
     * Gets asset area.
     *
     * @return string
     */
    public function getArea()
    {
        $area = $this->getArgument('area');
        $allowed = $this->getAllowedAreas();

        if ($area) {
            if (! $this->isAllowedArea($area)) {
                throw new AssetException('Illegal Asset Area: Assets can be only assigned to '.implode(', ', $allowed));
            }

            return $area;
        }

        return reset($allowed);
    }

    /**
     * Get asset placement.
     *
     * @return string
     */
    public function getPlacement()
    {
        if ($this->isTypeOf('style')) {
            return $this->getStylesMedia();
        }

        if ($this->isTypeOf('script')) {
            return $this->getScriptsPlacement();
        }
    }

    /**
     * Gets media of styles asset.
     *
     * @return string
     */
    private function getStylesMedia()
    {
        $media = $this->getArgument('media');
        $allowed = $this->getAllowedMedia();

        if ($media) {
            if (! $this->isAllowedMedia($media)) {
                throw new AssetException('Illegal media: Style assets acceptable madia '.implode(', ', $allowed));
            }

            return $media;
        }

        return reset($allowed);
    }

    /**
     * Gets placement of scripts asset.
     *
     * @return bool
     */
    private function getScriptsPlacement()
    {
        $placement = $this->getArgument('placement');
        $allowed = $this->getAllowedPlacements();

        if ($placement) {
            if (! $this->isAllowedPlacement($placement)) {
                throw new AssetException('Illegal placement: Script assets can be only placed in '.implode(', ', $allowed));
            }

            if ($this->isPlacement('head')) {
                return $placement;
            }
        }

        return 'footer';
    }

    /**
     * Gets the asset action type.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Sets the asset action type.
     *
     * @param string $action the action
     *
     * @return self
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Gets the asset allowed areas.
     *
     * @return array
     */
    public function getAllowedAreas()
    {
        return $this->settings->get('assets')['areas'];
    }

    /**
     * Gets the asset allowed area.
     *
     * @param string $area
     *
     * @return string
     */
    public function getAllowedArea($area)
    {
        return $this->getAllowedAreas()[$area];
    }

    /**
     * Check if asset area is allowed.
     *
     * @param  string  $area
     *
     * @return bool
     */
    public function isAllowedArea($area)
    {
        return array_search($area, $this->getAllowedAreas());
    }

    /**
     * Gets the asset allowed types.
     *
     * @return array
     */
    public function getAllowedTypes()
    {
        return $this->settings->get('assets')['types'];
    }

    /**
     * Check if asset type is allowed.
     *
     * @param  string  $type
     *
     * @return bool
     */
    public function isAllowedType($type)
    {
        return in_array($type, $this->getAllowedTypes());
    }

    /**
     * Check if asset type is allowed.
     *
     * @param  string  $type
     *
     * @return bool
     */
    public function isTypeOf($type)
    {
        return $this->getType() === $type;
    }

    /**
     * Gets the asset allowed places.
     *
     * @return array
     */
    public function getAllowedPlacements()
    {
        return $this->settings->get('assets')['placements'];
    }

    /**
     * Check if asset placement.
     *
     * @param  string  $placement
     *
     * @return bool
     */
    public function isPlacement($placement)
    {
        return $this->getArgument('placement') === $placement;
    }

    /**
     * Check if asset placement is allowed.
     *
     * @param  string  $placement
     *
     * @return bool
     */
    public function isAllowedPlacement($placement)
    {
        return in_array($placement, $this->getAllowedPlacements());
    }

    /**
     * Gets the asset allowed media.
     *
     * @return array
     */
    public function getAllowedMedia()
    {
        return $this->settings->get('assets')['media'];
    }

    /**
     * Check if asset media is allowed.
     *
     * @param  string  $media
     *
     * @return bool
     */
    public function isAllowedMedia($media)
    {
        return in_array($media, $this->getAllowedMedia());
    }
}
