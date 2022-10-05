<?php

namespace Limsum\UrlMakers;

use Illuminate\Support\Arr;
use Limsum\Contracts\LimsumUrlMaker;
use Limsum\Http\Controllers\ColorBlockController;

class ColorBlockUrl implements LimsumUrlMaker
{
    use HasSize, HasExtension;

    /**
     * Image color.
     *
     * @var string
     */
    protected string $color;

    /**
     * Supported extensions.
     *
     * @var array
     */
    protected array $allowedExtensions = [];

    /**
     * Predefined Types.
     *
     * @var array
     */
    protected array $types = [];

    /**
     * Initial Config.
     *
     * @var array
     */
    protected array $config = [];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config             = $config;
        $this->reset();
    }

    /**
     * Reset to initial state.
     *
     * @return static
     */
    public function reset(): static
    {
        $this->width             = (float) Arr::get($this->config, 'default.width', 100);
        $this->height            = (float) Arr::get($this->config, 'default.height', 100);
        $this->color             = (string) Arr::get($this->config, 'default.color', '#808080');
        $this->extension         = (string) Arr::get($this->config, 'default.extension', 'svg');
        $this->allowedExtensions = (array) Arr::get($this->config, 'extensions', []);
        $this->types             = (array) Arr::get($this->config, 'types', []);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function url(array|string $params = [], bool $reset = true): string
    {
        if (is_string($params)) {
            $this->type($params);
        }
        if (!in_array($this->extension, $this->allowedExtensions)) {
            throw new \InvalidArgumentException("Extension [{$this->extension}] not allowed.");
        }

        $url = route(config('lorem-image.route.name_prefix') . '.color-block', $this->routeParams(is_array($params) ? $params : []));

        if ($reset) {
            $this->reset();
        }

        return $url;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function routeParams(array $params = []): array
    {
        return [
            'extension'                                 => $params['extension'] ?? $this->extension,
            ColorBlockController::$widthParameterName   => $params['width']     ?? $this->width,
            ColorBlockController::$heightParameterName  => $params['height']    ?? $this->height,
            ColorBlockController::$colorParameterName   => $params['color']     ?? $this->color,
        ];
    }

    /**
     * @param string $color
     *
     * @return static
     */
    public function color(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return static
     */
    public function black(): static
    {
        return $this->color('#000000');
    }

    /**
     * @return static
     */
    public function white(): static
    {
        return $this->color('#FFFFFF');
    }

    /**
     * @return static
     */
    public function svg(): static
    {
        return $this->extension(__FUNCTION__);
    }

    /**
     * @return static
     */
    public function png(): static
    {
        return $this->extension(__FUNCTION__);
    }

    /**
     * @return static
     */
    public function jpg(): static
    {
        return $this->extension(__FUNCTION__);
    }

    /**
     * @param string $type
     *
     * @return static
     */
    public function type(string $type): static
    {
        if (!isset($this->types[ $type ]) || !is_array($data = $this->types[ $type ])) {
            throw new \InvalidArgumentException("Type [{$type}] not supported.");
        }

        foreach (
            [
                'width',
                'height',
                'color',
                'extension',
            ] as $key
        ) {
            if (isset($data[ $key ])) {
                $this->$key = $data[ $key ];
            }
        }


        return $this;
    }
}
