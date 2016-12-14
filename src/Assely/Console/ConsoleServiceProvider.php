<?php

namespace Assely\Console;

use WP_CLI;
use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * List of commands to register.
     *
     * @var array
     */
    protected $commands = [
        'Assely\Foundation\Console\MakeCommand',
        'Assely\Foundation\Console\InfoCommand',
        'Assely\Foundation\Console\ClearCommand',
        'Assely\Foundation\Console\SaltsCommand',
    ];

    /**
     * Boot console services.
     *
     * @return void
     */
    public function boot()
    {
        // Don't bootstrap service if
        // wp-cli is not present.
        if (! (defined('WP_CLI') && WP_CLI)) {
            return;
        }

        $this->registerCommands($this->commands);
    }

    /**
     * Register console services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('command', function () {
            return new Command;
        });

        $this->app->alias('command', Command::class);
    }

    /**
     * Register commands.
     *
     * @param array $commands
     *
     * @return void
     */
    public function registerCommands(array $commands)
    {
        foreach ($commands as $command) {
            $instance = $this->app->make($command);

            $instance->boot($this->app);

            $this->addCommand($instance->signature, [$instance, 'register'], [
                'shortdesc' => $instance->description,
            ]);
        }
    }

    /**
     * Add wp-cli command.
     *
     * @param string $name
     * @param string $class
     * @param arrat $arguments
     *
     * @return bool
     */
    public function addCommand($name, $command, array $arguments = [])
    {
        return WP_CLI::add_command($name, $command, $arguments);
    }
}
