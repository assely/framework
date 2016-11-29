<?php

namespace Assely\Foundation\Console;

use Assely\Console\Command;
use Illuminate\Support\Str;

class SaltsCommand extends Command
{
    /**
     * Command singnature.
     *
     * @var string
     */
    public $signature = 'assely:salts';

    /**
     * Command description.
     *
     * @var string
     */
    public $description = 'Generate Assely application salts.';

    /**
     * Salts variables formats.
     *
     * @var array
     */
    protected $saltsFormats = [
        'env' => "%s='%s'",
        'yaml' => '%s: "%s"',
        'constant' => "define('%s', '%s');",
    ];

    /**
     * Salts variables names.
     *
     * @var array
     */
    protected $saltsVariables = [
        'AUTH_KEY',
        'SECURE_AUTH_KEY',
        'LOGGED_IN_KEY',
        'NONCE_KEY',
        'AUTH_SALT',
        'SECURE_AUTH_SALT',
        'LOGGED_IN_SALT',
        'NONCE_SALT',
    ];

    /**
     * Randommize WordPress salts.
     *
     * ## OPTIONS
     *
     * [--format=<format>]
     * : Format output.
     * ---
     * default: env
     * options:
     *   - env
     *   - yaml
     *   - constant
     * ---
     *
     * ## EXAMPLE
     *
     *     wp assely:salts generate --format="yaml"
     */
    public function generate()
    {
        $this->renderSalts($this->saltsFormats[$this->getOption('format', 'env')]);
    }

    /**
     * Render WordPress salts.
     *
     * @param  array $delimiters
     *
     * @return void
     */
    protected function renderSalts($format)
    {
        foreach ($this->saltsVariables as $name) {
            if ($this->getOption('format') === 'yaml') {
                $name = strtolower($name);
            }

            $this->line(sprintf($format, $name, base64_encode(Str::randomBytes(48))));
        }
    }
}
