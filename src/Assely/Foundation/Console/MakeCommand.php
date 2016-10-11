<?php

namespace Assely\Foundation\Console;

use Assely\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Mustache_Engine as StubEngine;

class MakeCommand extends Command
{
    /**
     * Command singnature.
     *
     * @var string
     */
    public $signature = 'assely:make';

    /**
     * Command description.
     *
     * @var string
     */
    public $description = 'Scafford Assely application post types, metaboxes, taxonomies etc.';

    /**
     * Construct make command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct(
        Filesystem $filesystem,
        StubEngine $stub
    ) {
        $this->filesystem = $filesystem;
        $this->stub = $stub;
    }

    /**
     * Scafford custom post type.
     *
     * ## OPTIONS
     *
     * <classname>
     * : Name of the post type class.
     *
     * [--slug=<slug>]
     * : Specify post type slug. If this option is not provided it will slugify post type name.
     *
     * ## EXAMPLE
     *
     *     wp assely:make posttype Movies
     *
     */
    public function posttype()
    {
        $this->setClassname($this->getArgument(0));

        $this->generateClass('Posttypes', 'posttype.stub', [
            'classname' => $this->getClassname(),
            'slug' => $this->getOption('slug', Str::snake($this->getClassname())),
        ]);
    }

    /**
     * Scafford custom taxonomy.
     *
     * ## OPTIONS
     *
     * <classname>
     * : Name of the taxonomy class.
     *
     * [--slug=<slug>]
     * : Specify taxonomy slug. If this option is not provided it will slugify taxonomy name.
     *
     * [--belongsto=<classname>]
     * : Specify where taxonomy belongs to.
     *
     * ## EXAMPLE
     *
     *     wp assely:make taxonomy Grenes --belongsto="App\Posttypes\Movies"
     *
     */
    public function taxonomy()
    {
        $this->setClassname($this->getArgument(0));

        $this->generateClass('Taxonomies', 'taxonomy.stub', [
            'classname' => $this->getClassname(),
            'slug' => $this->getOption('slug', Str::snake($this->getClassname())),
            'belongsto' => $this->getOption('belongsto', '//'),
        ]);
    }

    /**
     * Scafford custom metabox.
     *
     * ## OPTIONS
     *
     * <classname>
     * : Name of the metabox class.
     *
     * [--slug=<slug>]
     * : Specify metabox slug. If this option is not provided it will slugify metabox name.
     *
     * [--belongsto=<classname>]
     * : Specify where metabox belongs to.
     *
     * ## EXAMPLE
     *
     *     wp assely:make metabox MovieDetails --belongsto="App\Posttypes\Movies"
     *
     */
    public function metabox()
    {
        $this->setClassname($this->getArgument(0));

        $this->generateClass('Metaboxes', 'metabox.stub', [
            'classname' => $this->getClassname(),
            'slug' => $this->getOption('slug', Str::snake($this->getClassname())),
            'belongsto' => $this->getOption('belongsto', '//'),
        ]);
    }

    /**
     * Scafford user profile.
     *
     * ## OPTIONS
     *
     * <classname>
     * : Name of the profile class.
     *
     * [--slug=<slug>]
     * : Specify profile slug. If this option is not provided it will slugify profile name.
     *
     * [--belongsto=<classname>]
     * : Specify where profile belongs to.
     *
     * ## EXAMPLE
     *
     *     wp assely:make profile WatchedMovies --belongsto="App\User"
     *
     */
    public function profile()
    {
        $this->setClassname($this->getArgument(0));

        $this->generateClass('Profiles', 'profile.stub', [
            'classname' => $this->getClassname(),
            'slug' => $this->getOption('slug', Str::snake($this->getClassname())),
            'belongsto' => $this->getOption('belongsto', '//'),
        ]);
    }

    /**
     * Scafford custom widget.
     *
     * ## OPTIONS
     *
     * <classname>
     * : Name of the widget class.
     *
     * [--slug=<slug>]
     * : Specify widget slug. If this option is not provided it will slugify widget name.
     *
     * ## EXAMPLE
     *
     *     wp assely:make widget LastestMovies
     *
     */
    public function widget()
    {
        $this->setClassname($this->getArgument(0));

        $slug = $this->getOption('slug', Str::snake($this->getClassname()));

        $this->generateClass('Support/Widgets', 'widget.stub', [
            'classname' => $this->getClassname(),
            'slug' => $slug,
        ]);

        $this->generateView(
            'widget/form.stub',
            "widgets/{$slug}",
            'form',
            ['slug' => $slug]
        );

        $this->generateView(
            'widget/view.stub',
            "widgets/{$slug}",
            'view'
        );
    }

    /**
     * Scafford controller.
     *
     * ## OPTIONS
     *
     * <classname>
     * : Name of the controller class.
     *
     * ## EXAMPLE
     *
     *     wp assely:make controller MoviesController
     *
     */
    public function controller()
    {
        $this->setClassname($this->getArgument(0));

        $this->generateClass('Http/Controllers', 'controller.stub', [
            'classname' => $this->getClassname(),
        ]);
    }

    /**
     * Scafford custom command.
     *
     * ## OPTIONS
     *
     * <classname>
     * : Name of the command class.
     *
     * [--command=<command>]
     * : Command name.
     *
     * [--method=<method>]
     * : Name of the command method.
     *
     * ## EXAMPLE
     *
     *     wp assely:make command RandomMovie --command="app:movie" --method="random"
     *
     */
    public function command($arguments, $options)
    {
        $this->setClassname($this->getArgument(0));

        if (! $this->getOption('signature')) {
            return $this->error('You must specify command signature [--signature=<signature>].');
        }

        if (! $this->getOption('method')) {
            return $this->error('You must specify the name of command method name [--method=<method>].');
        }

        $this->generateClass('Console/Commands', 'command.stub', [
            'classname' => $this->getClassname(),
            'signature' => $this->getOption('signature'),
            'method' => $this->getOption('method'),
        ]);
    }

    /**
     * Generate class for singularity.
     *
     * @param  string $dir
     * @param  string $stub
     * @param  array $arguments
     *
     * @return void
     */
    protected function generateClass($dir, $stub, array $arguments = [])
    {
        $this->line($this->colorize('Crafting:') . " Generating [{$this->getClassname()}] class...");

        $base = $this->config->get('app.directory');
        $file = "{$base}/app/{$dir}/{$this->getClassname()}.php";

        if ($this->filesystem->exists($file)) {
            $this->confirm("[{$this->getClassname()}] class already exists. Want to overwrite?");
        }

        $template = $this->filesystem->get(__DIR__ . "/stubs/{$stub}");

        $this->filesystem->put($file, $this->stub->render($template, $arguments));

        $this->success("{$this->getClassname()} created successfully.");
    }

    /**
     * Generate view template file.
     *
     * @param  string $stub
     * @param  string $dir
     * @param  string $file
     * @param  array  $arguments
     *
     * @return void
     */
    protected function generateView($stub, $dir, $file, array $arguments = [])
    {
        $this->line($this->colorize('Crafting:') . " Generating [{$file}] view...");

        $base = $this->config->get('app.directory');
        $directory = "{$base}/resources/views/{$dir}";
        $file = "{$directory}/{$file}.blade.php";

        if (! $this->filesystem->exists($directory)) {
            $this->filesystem->makeDirectory($directory);
        }

        if ($this->filesystem->exists($file)) {
            $this->confirm("View template [{$file}] already exists. Want to overwrite?");
        }

        $template = $this->filesystem->get(__DIR__ . "/stubs/{$stub}");

        $this->filesystem->put($file, $this->stub->render($template, $arguments));

        $this->success("View template [{$file}] created successfully.");
    }

    /**
     * Get classname.
     *
     * @return string
     */
    protected function getClassname()
    {
        return $this->classname;
    }

    /**
     * Set classname.
     *
     * @param string $name
     *
     * @return self
     */
    protected function setClassname($name)
    {
        $this->classname = Str::studly($name);

        return $this;
    }
}
