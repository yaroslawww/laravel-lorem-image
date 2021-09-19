# Laravel lorem image url generator.

Generates a configured preview image.

## Installation

Install the package via composer:

```bash
composer require yaroslawww/laravel-lorem-image
# Or only for development purposes
composer require yaroslawww/laravel-lorem-image --dev
```

Optionally you can publish the config file with:

```bash
php artisan vendor:publish --provider="Limsum\ServiceProvider" --tag="config"
```

If you need to use `color-block` driver, then please add routes to web.php
```injectablephp
\Limsum\Facades\Limsum::routes();
```

## Usage

```injectablephp
\Limsum::url();
// or
\Limsum\Facades\Limsum::size(274, 200)->svg()->url();
\Limsum\Facades\Limsum::driver('color-block')->size(274, 200)->svg()->url();
\Limsum\Facades\Limsum::url([
    'width' => 274,
    'height' => 200
]);
\Limsum\Facades\Limsum::url('thumbnail');
\Limsum\Facades\Limsum::driver('lorem-picsum')->size(274, 200)->random()->url()
```

Example image for development purpose with lazy loading. (Lazy loading not includes in package)

```html
<img class="lazy"
    src="{{ \Limsum\Facades\Limsum::size(274, 200)->svg()->url() }}"
    data-src="{{ \Limsum\Facades\Limsum::driver('lorem-picsum')->size(274, 200)->random()->url() }}"
>
```

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/)
- [![](https://www.google.com/s2/favicons?domain=picsum.photos) picsum.photos](https://picsum.photos/)
  
