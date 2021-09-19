<?php

namespace Limsum\ColorBlock;

class ColorBlockJpg extends ColorBlockPng
{

    /**
     * @param $image
     */
    protected function makeImage($image)
    {
        imagejpeg($image);
    }

    /**
     * @return string
     */
    protected function mimeType():string
    {
        return 'image/jpeg';
    }
}
