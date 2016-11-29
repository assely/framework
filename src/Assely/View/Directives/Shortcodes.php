<?php

namespace Assely\View\Directives;

class Shortcodes
{
    /**
     * Ignore html shortcodes inside html elements.
     *
     * @var bool
     */
    protected $ignore;

    /**
     * Returns before widget markup.
     *
     * @param \Assely\Adapter\Adapter|string $key
     * @param array $arguments
     *
     * @return bool
     */
    public function setUp($ignore = false)
    {
        $this->ignore = $ignore;

        ob_start();
    }

    /**
     * Returns after widget markup.
     *
     * @return string
     */
    public function tearDown()
    {
        $content = ob_get_clean();

        return do_shortcode($content, $this->ignore);
    }
}
