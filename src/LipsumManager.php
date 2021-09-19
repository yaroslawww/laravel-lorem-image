<?php

namespace Limsum;

use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Limsum\Contracts\LimsumUrlMaker;
use Limsum\Http\Controllers\ColorBlockController;
use Limsum\UrlMakers\ColorBlockUrl;
use Limsum\UrlMakers\LoremPicsumUrl;

class LipsumManager
{

    /**
     * The application instance.
     *
     * @var Container
     */
    protected Container $app;

    /**
     * The array of resolved image drivers.
     *
     * @var array
     */
    protected array $drivers = [];

    /**
     * The registered custom driver creators.
     *
     * @var array
     */
    protected array $customCreators = [];

    /**
     * Create a new manager instance.
     *
     * @param Container $app
     *
     * @return void
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @param string|null $prefix
     * @param string|null $namePrefix
     */
    public function routes(?string $prefix = null, ?string $namePrefix = null): void
    {
        if ($prefix) {
            $this->app['config']['lorem-image.url.prefix'] = $prefix;
        } else {
            $prefix = $this->app['config']['lorem-image.url.prefix'];
        }
        if ($namePrefix) {
            $this->app['config']['lorem-image.url.name_prefix'] = $prefix;
        } else {
            $namePrefix = $this->app['config']['lorem-image.url.name_prefix'];
        }
        Route::prefix($prefix)
             ->as("{$namePrefix}.")
             ->where([
                 'extension' => '(' . implode('|', config('lorem-image.drivers.color-block.extensions')) . ')',
             ])
             ->group(function () {
                 Route::get('color-block.{extension}', ColorBlockController::class)
                      ->name('color-block');
             });
    }

    /**
     * Get a driver instance.
     *
     * @param string|null $name
     *
     * @return LimsumUrlMaker
     */
    public function driver(?string $name = null): LimsumUrlMaker
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->drivers[ $name ] = $this->get($name);
    }

    /**
     * Resolve the given broadcaster.
     *
     * @param string $name
     *
     * @return LimsumUrlMaker
     *
     * @throws InvalidArgumentException
     */
    protected function resolve(string $name): LimsumUrlMaker
    {
        $config = $this->getConfig($name) ?? [];

        if (isset($this->customCreators[ $name ])) {
            return $this->callCustomCreator($config);
        }

        $driverMethod = 'create' . ucfirst(Str::camel($name)) . 'Driver';

        if (!method_exists($this, $driverMethod)) {
            throw new InvalidArgumentException("Driver [{$name}] is not supported.");
        }

        return $this->{$driverMethod}($config);
    }

    /**
     * @param array $config
     *
     * @return LimsumUrlMaker
     */
    protected function createColorBlockDriver(array $config = []): LimsumUrlMaker
    {
        return new ColorBlockUrl($config);
    }

    /**
     * @param array $config
     *
     * @return LimsumUrlMaker
     */
    protected function createLoremPicsumDriver(array $config = []): LimsumUrlMaker
    {
        return new LoremPicsumUrl($config);
    }

    /**
     * Call a custom driver creator.
     *
     * @param array $config
     *
     * @return LimsumUrlMaker
     */
    protected function callCustomCreator(array $config): LimsumUrlMaker
    {
        return $this->customCreators[$config['driver']]($this->app, $config);
    }

    /**
     * Register a custom driver creator Closure.
     *
     * @param string $driver
     * @param Closure $callback
     *
     * @return static
     */
    public function extend(string $driver, Closure $callback): static
    {
        $this->customCreators[ $driver ] = $callback;

        return $this;
    }

    /**
     * Get the connection configuration.
     *
     * @param string|null $name
     *
     * @return array|null
     */
    protected function getConfig(?string $name): ?array
    {
        if (!is_null($name) && $name !== 'null') {
            return $this->app['config']["lorem-image.drivers.{$name}"];
        }

        return null;
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return $this->app['config']['lorem-image.default.driver'];
    }

    /**
     * Set the default driver name.
     *
     * @param string $name
     *
     * @return static
     */
    public function setDefaultDriver(string $name): static
    {
        $this->app['config']['broadcasting.default'] = $name;

        return $this;
    }

    /**
     * Attempt to get the connection from the local cache.
     *
     * @param string $name
     *
     * @return LimsumUrlMaker
     */
    protected function get(string $name): LimsumUrlMaker
    {
        return $this->drivers[ $name ] ?? $this->resolve($name);
    }

    /**
     * Delete resolved driver.
     *
     * @param string|null $name
     *
     * @return void
     */
    public function purge(?string $name = null)
    {
        $name = $name ?? $this->getDefaultDriver();

        unset($this->drivers[ $name ]);
    }

    /**
     * Forget all of the resolved driver instances.
     *
     * @return static
     */
    public function forgetDrivers(): static
    {
        $this->drivers = [];

        return $this;
    }

    /**
     * Get the application instance used by the manager.
     *
     * @return Container
     */
    public function getApplication(): Container
    {
        return $this->app;
    }

    /**
     * Set the application instance used by the manager.
     *
     * @param Container $app
     *
     * @return static
     */
    public function setApplication(Container $app): static
    {
        $this->app = $app;

        return $this;
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->driver()->$method(...$parameters);
    }
}
