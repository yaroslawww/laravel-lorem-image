<?php

namespace Limsum\Tests;

use Limsum\Facades\Limsum;

class LoremPicsumUrlTest extends TestCase
{

    /** @test */
    public function can_change_extension()
    {
        $this->assertStringContainsString('.webp', Limsum::driver('lorem-picsum')->webp()->url());
        $this->assertStringContainsString('.jpg', Limsum::driver('lorem-picsum')->jpg()->url());
    }

    /** @test */
    public function can_override_default_url()
    {
        config([
            'lorem-image.drivers.lorem-picsum.picsum_url' => 'http://my-url',
        ]);

        $this->assertStringContainsString('http://my-url/', Limsum::driver('lorem-picsum')->url());
    }

    /** @test */
    public function has_image_info_url()
    {
        $this->assertStringContainsString('/id/123/info', Limsum::driver('lorem-picsum')->imageInfoUrl(123));
    }

    /** @test */
    public function has_images_list_url()
    {
        $this->assertStringContainsString('/v2/list?page=123&limit=12', Limsum::driver('lorem-picsum')->listImagesUrl(123, 12));
    }

    /** @test */
    public function can_update_type()
    {
        config([
            'lorem-image.drivers.lorem-picsum.types' => [
                'thumb' => [
                    'blur' => 654,
                ],
            ],
        ]);

        $this->assertStringContainsString('blur=654', Limsum::driver('lorem-picsum')->url('thumb'));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Type [bla] not supported.');
        Limsum::driver('lorem-picsum')->url('bla');
    }

    /** @test */
    public function url_by_id()
    {
        $this->assertStringContainsString('/id/987321/', Limsum::driver('lorem-picsum')->id(987321)->url());
    }

    /** @test */
    public function url_by_seed()
    {
        $this->assertStringContainsString('/seed/987321/', Limsum::driver('lorem-picsum')->seed(987321)->url());
    }

    /** @test */
    public function url_with_grayscale()
    {
        $this->assertStringContainsString('grayscale=1', Limsum::driver('lorem-picsum')->grayscale()->url());
    }

    /** @test */
    public function url_with_blur()
    {
        $this->assertStringContainsString('blur=13', Limsum::driver('lorem-picsum')->blur(13)->url());
    }

    /** @test */
    public function random_url()
    {
        $this->assertStringContainsString('random=', Limsum::driver('lorem-picsum')->random()->url());
    }
}
