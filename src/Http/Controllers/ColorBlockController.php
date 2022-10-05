<?php

namespace Limsum\Http\Controllers;

use Illuminate\Http\Request;
use Limsum\ColorBlock\ColorBlockMaker;

class ColorBlockController extends LimsumController
{
    /**
     * @var string
     */
    public static string $widthParameterName = 'w';

    /**
     * @var string
     */
    public static string $heightParameterName = 'h';

    /**
     * @var string
     */
    public static string $colorParameterName = 'c';

    /**
     * @param  string  $extension
     * @param  Request  $request
     *
     * @return mixed
     */
    public function __invoke(string $extension, Request $request)
    {
        if (!in_array($extension, config('lorem-image.drivers.color-block.extensions', []))) {
            abort(404);
        }

        $width = (float) ($request->get(static::$widthParameterName, 100) ?? 100);
        if ($width < 0 || $width > 999999999) {
            $width = 100;
        }
        $height = (float) ($request->get(static::$heightParameterName, 100) ?? 100);
        if ($height < 0 || $height > 999999999) {
            $height = 100;
        }

        return (new ColorBlockMaker(
            $extension,
            $width,
            $height,
            $request->get(static::$colorParameterName, '#808080') ?: '#808080',
        ))->image();
    }
}
