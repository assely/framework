<?php

namespace Assely\Console;

use cli;
use WP_CLI;
use Exception;
use WP_CLI\Utils;
use Assely\Support\Descend;
use Assely\Foundation\Application;
use Assely\Support\Accessors\HasArguments;

class Command
{
    use HasArguments;

    /**
     * Command singnature.
     *
     * @var string
     */
    public $signature;

    /**
     * Application configs.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Application instance.
     *
     * @var \Assely\Foundation\Application
     */
    protected $app;

    /**
     * Default arguments.
     *
     * @var array
     */
    private $defaults = [];

    /**
     * Bootstrap command instance.
     *
     * @param \Assely\Foundation\Application $app the app
     *
     * @return self
     */
    public function boot(Application $app)
    {
        $this->app = $app;

        $this->config = $app->make('config');

        return $this;
    }

    /**
     * Register command.
     *
     * @param  array $arguments
     * @param  array $options
     *
     * @return mixed
     */
    public function register($arguments, $options)
    {
        $method = array_shift($arguments);

        $this->setArguments($arguments);
        $this->setOptions($options);

        if (method_exists($this, $method)) {
            try {
                return $this->app->call([$this, $method]);
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        }

        $this->error("You have to provide command argument [$this->signature <argument>].");
    }

    /**
     * Log success message to the console.
     *
     * @param string $content
     *
     * @return void
     */
    protected function success($content)
    {
        return WP_CLI::success($content);
    }

    /**
     * Log warning message to the console.
     *
     * @param string $content
     *
     * @return void
     */
    protected function warning($content)
    {
        return WP_CLI::warning($content);
    }

    /**
     * Log error message to the console.
     *
     * @param string $content
     *
     * @return void
     */
    protected function error($content)
    {
        return WP_CLI::error($content);
    }

    /**
     * Log message line to the console.
     *
     * @param string $content [description]
     *
     * @return void
     */
    protected function line($content)
    {
        return WP_CLI::log($content);
    }

    /**
     * Colorize message.
     *
     * @param string $content
     * @param string $color
     *
     * @return string
     */
    protected function colorize($content, $color = '%Y')
    {
        return WP_CLI::colorize("{$color}{$content}%n");
    }

    /**
     * Ask for confirmation.
     *
     * @param  string $content
     * @param  array  $arguments
     *
     * @return void
     */
    protected function confirm($content, $arguments = [])
    {
        return WP_CLI::confirm($content, $arguments);
    }

    /**
     * Ask for input.
     *
     * @param  string  $question
     * @param  mixed $default
     * @param  string  $marker
     *
     * @return void
     */
    protected function ask($question, $default = false, $marker = ':')
    {
        return cli\prompt($question, $default, $marker);
    }

    /**
     * Ask for choice.
     *
     * @param  string  $title
     * @param  array  $choices
     * @param  mixed $default
     *
     * @return void
     */
    protected function choice($title, array $choices, $default = false)
    {
        $this->line('Available choices:');

        return cli\menu($choices, $default, $title);
    }

    /**
     * Call WP-CLI command.
     *
     * @param string $command
     * @param string $arguments
     *
     * @return void
     */
    protected function call($command, $arguments = [])
    {
        return WP_CLI::run_command(explode(' ', $command), $arguments);
    }

    /**
     * Render tabular data.
     *
     * @param array $data
     *
     * @return void
     */
    protected function table(array $headers, array $data)
    {
        $dataset = [];

        foreach (array_values($data) as $value) {
            $dataset[] = array_combine($headers, $value);
        }

        return Utils\format_items('table', $dataset, $headers);
    }

    /**
     * Gets value of options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets value of options.
     *
     * @param array $options
     *
     * @return self
     */
    protected function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get option. Return default if option is not set.
     *
     * @param string $name
     * @param mixed $default
     *
     * @return mxied
     */
    public function getOption($name, $default = null)
    {
        return Descend::whileEmpty($this->options[$name], $default);
    }

    /**
     * Set option.
     *
     * @param string $name
     * @param mixed $value
     *
     * @return self
     */
    protected function setOption($name, $value)
    {
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * Gets the Application configs.
     *
     * @return \Illuminate\Config\Repository
     */
    public function getConfig()
    {
        return $this->config;
    }
}
