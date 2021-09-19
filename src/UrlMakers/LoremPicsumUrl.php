<?php

namespace Limsum\UrlMakers;

use Illuminate\Support\Arr;
use Limsum\Contracts\LimsumUrlMaker;

class LoremPicsumUrl implements LimsumUrlMaker
{
    use HasSize, HasExtension;

    /**
     * Lorem picsum site url.
     *
     * @var string
     */
    public string $url = 'https://picsum.photos';

    /**
     * Image grayscale.
     *
     * @var bool
     */
    protected bool $grayscale;

    /**
     * Image blur.
     *
     * @var bool|int
     */
    protected bool|int $blur;

    /**
     * Set to use random image.
     *
     * @var bool|int
     */
    protected bool|int $random;

    /**
     * Seed name.
     *
     * @var bool|string
     */
    protected bool|string $seed;

    /**
     * Set specific image ID.
     *
     * @var bool|int
     */
    protected bool|int $id;

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
        $this->config = $config;

        if (isset($this->config['picsum_url'])) {
            $this->url = (string) $this->config['picsum_url'];
        }
        $this->reset();
    }

    /**
     * Get information link about a specific image.
     *
     * @param int $id
     *
     * @return string
     */
    public function imageInfoUrl(int $id): string
    {
        return rtrim($this->url) . "/id/{$id}/info";
    }

    /**
     * Get a list of images.
     *
     * @param int $page
     * @param int $limit
     *
     * @return string
     */
    public function listImagesUrl(int $page = 1, int $limit = 30): string
    {
        return rtrim($this->url) . "/v2/list?page={$page}&limit={$limit}";
    }

    /**
     * Reset to initial state.
     *
     * @return static
     */
    public function reset(): static
    {
        $this->width     = (float) Arr::get($this->config, 'default.width', 100);
        $this->height    = (float) Arr::get($this->config, 'default.height', 100);
        $this->extension = (string) Arr::get($this->config, 'default.extension', 'jpg');
        $this->grayscale = (bool) Arr::get($this->config, 'default.grayscale', false);
        $this->blur      = Arr::get($this->config, 'default.blur', false);
        $this->random    = Arr::get($this->config, 'default.random', false);
        $this->seed      = Arr::get($this->config, 'default.seed', false);
        $this->id        = Arr::get($this->config, 'default.id', false);

        $this->types = (array) Arr::get($this->config, 'types', []);

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

        return $this->generateUrl(is_array($params) ? $params : []);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    protected function generateUrl(array $params): string
    {
        $url = rtrim($this->url);

        if (is_numeric($id = $params['id'] ?? $this->id)) {
            $url = "{$url}/id/{$id}";
        } elseif ($seed = $params['seed'] ?? $this->seed) {
            $url = "{$url}/seed/{$seed}";
        }

        $width  = $params['width']  ?? $this->width;
        $height = $params['height'] ?? $this->height;
        $url    = "{$url}/{$width}/{$height}";

        if ($extension = $params['extension'] ?? $this->extension) {
            $url = "{$url}.{$extension}";
        }

        $query = [];

        if ($params['grayscale'] ?? $this->grayscale) {
            $query['grayscale'] = 1;
        }

        if ($blur = $params['blur'] ?? $this->blur) {
            $query['blur'] = (int) $blur;
        }

        if ($random = $params['random'] ?? $this->random) {
            $query['random'] = $random === true ? uniqid() : $random;
        }

        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        return $url;
    }

    /**
     * @return static
     */
    public function jpg(): static
    {
        return $this->extension(__FUNCTION__);
    }

    /**
     * @return static
     */
    public function webp(): static
    {
        return $this->extension(__FUNCTION__);
    }

    /**
     * @param int|bool $id
     *
     * @return static
     */
    public function id(int|bool $id): static
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string|bool $seed
     *
     * @return static
     */
    public function seed(string|bool $seed): static
    {
        $this->seed = $seed;

        return $this;
    }

    /**
     * @param bool $grayscale
     *
     * @return static
     */
    public function grayscale(bool $grayscale = true): static
    {
        $this->grayscale = $grayscale;

        return $this;
    }

    /**
     * @param bool|int $blur
     *
     * @return static
     */
    public function blur(bool|int $blur = true): static
    {
        $this->blur = $blur;

        return $this;
    }

    /**
     * @param bool|int $random
     *
     * @return static
     */
    public function random(bool|int $random = true): static
    {
        $this->random = $random;

        return $this;
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
                'extension',
                'grayscale',
                'blur',
                'random',
                'seed',
                'id',
            ] as $key
        ) {
            if (array_key_exists($key, $data)) {
                $this->$key = $data[ $key ];
            }
        }


        return $this;
    }
}
