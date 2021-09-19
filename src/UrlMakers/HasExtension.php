<?php

namespace Limsum\UrlMakers;

trait HasExtension
{

    /**
     * Image extension.
     *
     * @var string
     */
    protected string $extension;

    /**
     * @param string $extension
     *
     * @return static
     */
    public function extension(string $extension): static
    {
        $this->extension = $extension;

        return $this;
    }
}
