<?php

namespace Limsum\ColorBlock;

class ColorBlockMaker
{

    /**
     * @var string
     */
    protected string $extension;

    /**
     * @var float
     */
    protected float $with;

    /**
     * @var float
     */
    protected float $height;

    /**
     * @var string
     */
    protected string $color;

    /**
     * @param string $extension
     * @param float $with
     * @param float $height
     * @param string $color
     */
    public function __construct(string $extension, float $with = 100, float $height = 100, string $color = '#808080')
    {
        $this->extension = $extension;
        $this->with      = $with;
        $this->height    = $height;
        $this->color     = $color;
    }

    public function image()
    {
        $colorBlockImage =  match ($this->extension) {
            'png' => ( new ColorBlockPng($this->with, $this->height, $this->color) ),
            'jpg', 'jpeg' => ( new ColorBlockJpg($this->with, $this->height, $this->color) ),
            default => ( new ColorBlockSvg($this->with, $this->height, $this->color) ),
        };

        return $colorBlockImage->image();
    }
}
