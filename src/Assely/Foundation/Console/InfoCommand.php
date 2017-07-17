<?php

namespace Assely\Foundation\Console;

use Assely\Console\Command;

class InfoCommand extends Command
{
    /**
     * Command singnature.
     *
     * @var string
     */
    public $signature = 'assely:info';

    /**
     * Command description.
     *
     * @var string
     */
    public $description = 'Display informations about Assely application.';

    /**
     * Show registered assets in theme area.
     *
     * ## OPTIONS
     *
     * [--all]
     * : Show all assets from all areas.
     *
     * ## EXAMPLE
     *
     *     wp assely:show assets
     */
    public function assets()
    {
        $assets = $this->app->make('assets.collection')->all();

        if (! $this->getOption('all')) {
            $assets = array_filter($assets, function ($asset) {
                return $asset->getArea() !== 'admin';
            });
        }

        $headers = ['Slug', 'Type', 'Path', 'Area', 'Placement', 'Execution', 'Version'];

        $dataset = array_map(function ($asset) {
            return [
                $asset->getSlug(),
                $asset->getType(),
                $asset->getArgument('path'),
                $asset->getArea(),
                $asset->getPlacement(),
                $asset->getExecution(),
                $asset->getArgument('version'),
            ];
        }, $assets);

        $this->table($headers, $dataset);
    }

    /**
     * Show registered routes.
     *
     * ## EXAMPLE
     *
     *     wp assely:show routes
     */
    public function routes()
    {
        $routes = $this->app->make('routes.collection')->getAll();

        $headers = ['Condition', 'Filter', 'Action', 'Parameters', 'Pattern', 'Guid'];

        $dataset = array_map(function ($route) {
            return [
                $route->getCondition(),
                $route->getFilter(),
                $route->getAction(),
                $route->getParameters(),
                $route->getPattern(),
                $route->getGuid(),
            ];
        }, $routes);

        $this->table($headers, $dataset);
    }

    /**
     * Show registered sidebars.
     *
     * ## EXAMPLE
     *
     *     wp assely:show sidebars
     */
    public function sidebars()
    {
        $sidebars = $this->app->make('sidebars.collection')->all();

        $headers = ['Slug', 'Title', 'Description', 'Has widgets'];

        $dataset = array_map(function ($sidebar) {
            return [
                $sidebar->getSlug(),
                $sidebar->getSingular(),
                $sidebar->getArgument('description'),
                $sidebar->hasWidgets() ? 'true' : 'false',
            ];
        }, $sidebars);

        $this->table($headers, $dataset);
    }

    /**
     * Show registered menus.
     *
     * ## EXAMPLE
     *
     *     wp assely:show menus
     */
    public function menus()
    {
        $menus = $this->app->make('menus.collection')->all();

        $headers = ['Slug', 'Top level items', 'Active'];

        $dataset = array_map(function ($menu) {
            return [
                $menu->getSlug(),
                count($menu->items()),
                $menu->isActive() ? 'true' : 'false',
            ];
        }, $menus);

        $this->table($headers, $dataset);
    }

    /**
     * Show registered ajaxes actions.
     *
     * ## EXAMPLE
     *
     *     wp assely:show ajaxes
     */
    public function ajaxes()
    {
        $ajaxes = $this->app->make('ajaxes.collection')->all();

        $headers = ['AJAX action', 'Accessibility'];

        $dataset = array_map(function ($action) {
            return [
                $action->getSlug(),
                $action->getArgument('accessibility'),
            ];
        }, $ajaxes);

        $this->table($headers, $dataset);
    }
}
