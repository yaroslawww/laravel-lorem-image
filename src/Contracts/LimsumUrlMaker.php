<?php

namespace Limsum\Contracts;

interface LimsumUrlMaker
{
    /**
     * Generate url.
     *
     * @param array|string $params
     * @param bool $reset Reset state after url generation.
     *
     * @return string
     */
    public function url(array|string $params = [], bool $reset = true): string;
}
