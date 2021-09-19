<?php

namespace Limsum\ColorBlock;

use Illuminate\Support\Facades\Response;

class ColorBlockSvg extends AbstractColorBlockImage
{
    public function image(): \Illuminate\Http\Response
    {
        return Response::make("
        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 {$this->with} {$this->height}'>
          <rect width='{$this->with}' height='{$this->height}'
            fill='{$this->color}'
               />
        </svg>
       ", 200, [
           'Content-Type' => 'image/svg+xml',
       ]);
    }
}
