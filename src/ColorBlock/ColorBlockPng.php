<?php

namespace Limsum\ColorBlock;

use Illuminate\Support\Facades\Response;

class ColorBlockPng extends AbstractColorBlockImage
{
    public function image(): \Illuminate\Http\Response
    {
        if (!extension_loaded('gd')) {
            throw new \Exception('FG extension not loaded.');
        }

        $image = imagecreatetruecolor($this->with, $this->height);

        $color = $this->hexColorAllocate($image, $this->color);

        imagefilledrectangle($image, 0, 0, $this->with, $this->height, $color);

        ob_start();
        $this->makeImage($image);
        $imageData = ob_get_contents();
        ob_end_clean();

        return Response::make($imageData, 200, [
            'Content-Type' => $this->mimeType(),
        ]);
    }

    /**
     * @param $image
     * @param $hex
     *
     * @return bool|int
     */
    protected function hexColorAllocate($image, $hex): bool|int
    {
        $hex = ltrim($hex, '#');
        $r   = hexdec(substr($hex, 0, 2));
        $g   = hexdec(substr($hex, 2, 2));
        $b   = hexdec(substr($hex, 4, 2));

        return imagecolorallocate($image, $r, $g, $b);
    }

    /**
     * @param $image
     */
    protected function makeImage($image)
    {
        imagepng($image);
    }

    /**
     * @return string
     */
    protected function mimeType():string
    {
        return 'image/png';
    }
}
