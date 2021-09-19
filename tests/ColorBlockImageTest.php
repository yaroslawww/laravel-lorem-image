<?php

namespace Limsum\Tests;

use Limsum\Facades\Limsum;

class ColorBlockImageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Limsum::routes();
    }

    /** @test */
    public function svg_image()
    {
        $url = Limsum::driver('color-block')->url([
            'width'  => 50,
            'height' => 25,
            'color'  => '#202020',
        ]);

        $response = $this->get($url);

        $response->assertHeader('Content-Type', 'image/svg+xml');
        $this->assertStringContainsString("viewBox='0 0 50 25'", $response->content());
        $this->assertStringContainsString("fill='#202020'", $response->content());
        $this->assertStringContainsString("width='50'", $response->content());
        $this->assertStringContainsString("height='25'", $response->content());
    }

    /** @test */
    public function png_image()
    {
        $url = Limsum::driver('color-block')->url([
            'width'      => 50,
            'height'     => 25,
            'color'      => '#202020',
            'extension'  => 'png',
        ]);

        $response = $this->get($url);

        $response->assertHeader('Content-Type', 'image/png');
        $this->assertStringContainsString('PNG', $response->content());
    }

    /** @test */
    public function jpg_image()
    {
        $url = Limsum::driver('color-block')->url([
            'width'      => 50,
            'height'     => 25,
            'color'      => '#202020',
            'extension'  => 'jpg',
        ]);

        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/jpeg');
    }
}
