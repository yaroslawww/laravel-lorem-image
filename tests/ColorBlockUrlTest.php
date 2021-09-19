<?php

namespace Limsum\Tests;

use Limsum\Facades\Limsum;

class ColorBlockUrlTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Limsum::routes();
    }

    /** @test */
    public function can_update_color()
    {
        $this->assertStringContainsString('&c=%23125478', Limsum::driver('color-block')->color('#125478')->url());
        $this->assertStringContainsString('&c=%23000000', Limsum::driver('color-block')->black()->url());
        $this->assertStringContainsString('&c=%23FFFFFF', Limsum::driver('color-block')->white()->url());
    }

    /** @test */
    public function can_change_extension()
    {
        $this->assertStringContainsString('/color-block.svg', Limsum::driver('color-block')->svg()->url());
        $this->assertStringContainsString('/color-block.png', Limsum::driver('color-block')->png()->url());
        $this->assertStringContainsString('/color-block.jpg', Limsum::driver('color-block')->jpg()->url());

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Extension [bla] not allowed.');
        Limsum::driver('color-block')->extension('bla')->url();
    }

    /** @test */
    public function can_update_type()
    {
        config([
            'lorem-image.drivers.color-block.types' => [
                'thumb' => [
                    'color' => '#665544',
                ],
            ],
        ]);

        $this->assertStringContainsString('/color-block.png?w=100&h=100&c=%23665544', Limsum::driver('color-block')->png()->url('thumb'));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Type [bla] not supported.');
        Limsum::driver('color-block')->url('bla');
    }

    /** @test */
    public function can_update_height()
    {
        $this->assertStringContainsString('?w=100&h=13', Limsum::driver('color-block')->height(13)->url());
    }

    /** @test */
    public function can_update_size()
    {
        $this->assertStringContainsString('?w=23&h=24', Limsum::driver('color-block')->size(23, 24)->url());
    }

    /** @test */
    public function can_set_square()
    {
        $this->assertStringContainsString('?w=45&h=45', Limsum::driver('color-block')->square(45)->url());
    }
}
