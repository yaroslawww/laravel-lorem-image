<?php

namespace Limsum\Tests;

use Limsum\Facades\Limsum;
use Limsum\UrlMakers\ColorBlockUrl;
use Limsum\UrlMakers\LoremPicsumUrl;

class LimsumManagerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Limsum::routes();
    }

    /** @test */
    public function color_block_is_default_driver()
    {
        $this->assertInstanceOf(ColorBlockUrl::class, Limsum::driver());
        $this->assertStringContainsString('/color-block.', Limsum::url());
    }

    /** @test */
    public function config_has_lorem_picsum_driver()
    {
        $this->assertInstanceOf(LoremPicsumUrl::class, Limsum::driver('lorem-picsum'));
        $this->assertStringContainsString('https://picsum.photos/', Limsum::driver('lorem-picsum')->url());
    }

    /** @test */
    public function null_driver_return_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        Limsum::driver('null');
    }

    /** @test */
    public function default_driver_can_be_changed()
    {
        Limsum::setDefaultDriver('lorem-picsum');
        $this->assertInstanceOf(LoremPicsumUrl::class, Limsum::driver());
        $this->assertStringContainsString('https://picsum.photos/', Limsum::url());
    }

    /** @test */
    public function driver_can_be_extended()
    {
        Limsum::extend('lorem-picsum', function (array $config) {
            return new ColorBlockUrl([
                'extensions' => [
                    'svg',
                ],
            ]);
        });

        $this->assertInstanceOf(ColorBlockUrl::class, Limsum::driver('lorem-picsum'));
        $this->assertStringContainsString('/color-block.', Limsum::driver('lorem-picsum')->url());
    }

    /** @test */
    public function update_the_application_instance_used_by_manager()
    {
        $mock = $this->mock(\Illuminate\Contracts\Container\Container::class);
        Limsum::setApplication($mock);
        $this->assertEquals($mock, Limsum::getApplication());
    }

    /** @test */
    public function remove_specific_driver()
    {
        $defaultDriver = Limsum::driver()->width(300);
        $this->assertStringContainsString('w=300', Limsum::url([], false));
        $this->assertStringContainsString('w=300', $defaultDriver->url([], false));
        Limsum::purge();
        $this->assertStringContainsString('w=100', Limsum::url([], false));
        $this->assertStringContainsString('w=300', $defaultDriver->url([], false));
    }

    /** @test */
    public function remove_all_drivers()
    {
        $defaultDriver = Limsum::driver()->width(300);
        $this->assertStringContainsString('w=300', Limsum::url([], false));
        $this->assertStringContainsString('w=300', $defaultDriver->url([], false));
        Limsum::forgetDrivers();
        $this->assertStringContainsString('w=100', Limsum::url([], false));
        $this->assertStringContainsString('w=300', $defaultDriver->url([], false));
    }
}
