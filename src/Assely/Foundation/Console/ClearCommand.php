<?php

namespace Assely\Foundation\Console;

use Assely\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearCommand extends Command
{
    /**
     * Command singnature.
     *
     * @var string
     */
    public $signature = 'assely:clear';

    /**
     * Command description.
     *
     * @var string
     */
    public $description = 'Clear Assely application caches, temporary files etc.';

    /**
     * Contruct clear command.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Clear application expired caches.
     *
     * ## OPTIONS
     *
     * [--all]
     * : Enforce clearing all cache.
     *
     * ## EXAMPLE
     *
     *     wp assely:clear cache
     *
     */
    public function cache()
    {
        if ($this->getOption('all')) {
            return $this->call('transient delete-all');
        }

        $this->call('transient delete-expired');
    }

    /**
     * Clear views caches.
     *
     * ## EXAMPLE
     *
     *     wp assely:clear views
     *
     */
    public function views()
    {
        $directory = "{$this->config->get('app.directory')}/storage/framework/views";

        $this->filesystem->cleanDirectory($directory);

        $this->success("View cache cleared successfully.");
    }

    /**
     * Clear application rewrite rules.
     *
     * ## EXAMPLE
     *
     *     wp assely:clear rewrites
     *
     */
    public function rewrites()
    {
        $this->call('rewrite flush');
    }
}
