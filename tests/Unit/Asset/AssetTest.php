<?php

use Assely\Asset\Asset;
use Assely\Asset\AssetException;
use Brain\Monkey\Functions;

class AssetTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_by_default_register_asset_to_theme_area()
    {
        $asset = $this->getAsset();

        $this->assertEquals('theme', $asset->getArgument('area'));
    }

    /**
     * @test
     */
    public function it_should_by_default_set_asset_media_to_screen()
    {
        $asset = $this->getAsset();

        $this->assertEquals('screen', $asset->getArgument('media'));
    }

    /**
     * @test
     */
    public function it_should_detect_css_file_and_set_style_type()
    {
        $asset = $this->getAsset();

        $this->assertEquals('style', $asset->getType());
    }

    /**
     * @test
     */
    public function it_should_detect_js_file_and_set_script_type()
    {
        $asset = $this->getAsset(['path' => 'js/app.js']);

        $this->assertEquals('script', $asset->getType());
    }

    /**
     * @test
     */
    public function it_should_not_detect_file_type_if_type_is_provided()
    {
        $asset = $this->getAsset(['type' => 'script']);

        $this->assertEquals('script', $asset->getType());
    }

    /**
     * @test
     */
    public function it_should_throw_if_provided_type_is_unallowed()
    {
        $asset = $this->getAsset(['type' => 'wrong']);

        $this->expectException(AssetException::class);

        $asset->getType();
    }

    /**
     * @test
     */
    public function it_detect_absolute_path_and_dont_modify_it()
    {
        $asset_http = $this->getAsset(['path' => 'http://www.example.com/app.js']);
        $asset_https = $this->getAsset(['path' => 'https://www.example.com/app.js']);
        $asset_schemeless = $this->getAsset(['path' => '//www.example.com/app.js']);

        $this->assertEquals('http://www.example.com/app.js', $asset_http->getPath());
        $this->assertEquals('https://www.example.com/app.js', $asset_https->getPath());
        $this->assertEquals('//www.example.com/app.js', $asset_schemeless->getPath());
    }

    /**
     * @test
     */
    public function it_resolve_assets_path_in_public_dir_if_provided_path_is_relative()
    {
        $jsAsset = $this->getAsset(['path' => 'js/app.js']);
        $cssAsset = $this->getAsset(['path' => 'css/app.css']);

        $this->assertEquals('path/public/js/app.js', $jsAsset->getPath());
        $this->assertEquals('path/public/css/app.css', $cssAsset->getPath());
    }

    /**
     * @param array $arguments
     */
    protected function getAsset($arguments = [])
    {
        Functions::expect('sanitize_title')->once()->andReturn('app');

        $filesystem = Mockery::mock('Illuminate\Filesystem\Filesystem');
        $hook = Mockery::mock('Assely\Hook\HookFactory');
        $settings = Mockery::mock('Assely\Config\FrameworkConfig');
        $config = Mockery::mock('Assely\Config\ApplicationConfig');

        $filesystem->shouldReceive('exists')->andReturn(true);

        $settings->shouldReceive('get')->withArgs(['assets'])->andReturn(
            require dirname(dirname(__DIR__)).'/../config/assets.php'
        );

        $config->shouldReceive('get')->withArgs(['app.path'])->andReturn('path');
        $config->shouldReceive('get')->withArgs(['app.directory'])->andReturn('directory');

        $asset = new Asset(
            $filesystem,
            $hook,
            $settings,
            $config
        );

        $asset->setSlug('app');
        $asset->setArguments(array_merge(['path' => 'css/app.css'], $arguments));

        return $asset;
    }
}
