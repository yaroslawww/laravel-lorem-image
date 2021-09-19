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

        $image = imagecreatetruecolor((int) $this->with, (int) $this->height);

        $color = $this->hexColorAllocate($image, $this->color);

        imagefilledrectangle($image, 0, 0, (int) $this->with, (int) $this->height, $color);

        ob_start();
        $this->makeImage($image);
        $imageData = ob_get_contents();
        ob_end_clean();

        imagedestroy($image);

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
        $r   = (int) hexdec(substr($hex, 0, 2));
        $g   = (int) hexdec(substr($hex, 2, 2));
        $b   = (int) hexdec(substr($hex, 4, 2));

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
