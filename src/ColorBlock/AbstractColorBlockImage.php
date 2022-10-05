<?php

namespace Limsum\ColorBlock;

use Illuminate\Http\Response;

abstract class AbstractColorBlockImage
{
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
     * @param float $with
     * @param float $height
     * @param string $color
     */
    public function __construct(float $with = 100, float $height = 100, string $color = '#808080')
    {
        $this->with   = $with;
        $this->height = $height;
        $this->color  = $color;
    }

    /**
     * @return Response
     * @throws \Exception
     */
    abstract public function image(): Response;
}
