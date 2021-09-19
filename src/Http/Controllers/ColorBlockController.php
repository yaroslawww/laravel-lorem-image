<?php

namespace Limsum\Http\Controllers;

use Illuminate\Http\Request;
use Limsum\ColorBlock\ColorBlockMaker;

class ColorBlockController extends LimsumController
{

    /**
     * @var string
     */
    public static string $withParameterName = 'w';

    /**
     * @var string
     */
    public static string $heightParameterName = 'h';

    /**
     * @var string
     */
    public static string $colorParameterName = 'c';

    /**
     * @param string $extension
     * @param Request $request
     *
     * @return mixed
     */
    public function __invoke(string $extension, Request $request)
    {
        if (!in_array($extension, config('lorem-image.drivers.color-block.extensions', []))) {
            abort(404);
        }

        return (new ColorBlockMaker(
            $extension,
            $request->get(static::$withParameterName, 100),
            $request->get(static::$heightParameterName, 100),
            $request->get(static::$colorParameterName, '#808080'),
        ))->image();
    }
}
