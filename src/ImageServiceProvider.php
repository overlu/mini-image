<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniImage;

use Intervention\Image\ImageManager;
use Mini\Contracts\Container\BindingResolutionException;
use Mini\Service\HttpServer\RouteService;
use Mini\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
{
    /**
     * Determines if Intervention Imagecache is installed
     *
     * @return boolean
     */
    private function cacheIsInstalled(): bool
    {
        return class_exists('Intervention\\Image\\ImageCache');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/image.php' => config_path('image.php')
        ]);

        // setup intervention/imagecache if package is installed
        $this->cacheIsInstalled() ? $this->bootstrapImageCache() : null;
    }

    /**
     * Register the service provider.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function register(): void
    {
        // merge default config
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/config.php',
            'image'
        );

        // create image
        $this->app->singleton('image', function ($app) {
            return new ImageManager($this->getImageConfig());
        });

        $this->app->alias('image', ImageManager::class);
    }

    /**
     * Bootstrap imagecache
     *
     * @return void
     * @throws BindingResolutionException
     */
    protected function bootstrapImageCache(): void
    {
        // imagecache route
        $route = config('image.route');
        if (is_string($route)) {
            RouteService::registerHttpRoute([
                'GET', $route . '/{template}/{filename:[\w\\.\\/\\-\\@\(\)\=]+}', 'Intervention\Image\ImageCacheController@getResponse'
            ]);
        }
    }

    /**
     * Return image configuration as array
     *
     * @return array
     */
    private function getImageConfig(): array
    {
        $config = config('image');

        if (is_null($config)) {
            return [];
        }

        return $config;
    }
}
