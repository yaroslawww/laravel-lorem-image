<?php

namespace Limsum\UrlMakers;

trait HasSize
{
    /**
     * Image width.
     *
     * @var float
     */
    protected float $width;

    /**
     * Image height.
     *
     * @var float
     */
    protected float $height;

    /**
     * @param float $width
     *
     * @return static
     */
    public function width(float $width): static
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @param float $height
     *
     * @return static
     */
    public function height(float $height): static
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @param float $size
     *
     * @return static
     */
    public function square(float $size): static
    {
        $this->width  = $size;
        $this->height = $size;

        return $this;
    }

    /**
     * @param float $width
     * @param float $height
     *
     * @return static
     */
    public function size(float $width, float $height): static
    {
        $this->width  = $width;
        $this->height = $height;

        return $this;
    }
}
