<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniImage\Facades;

use Mini\Facades\Facade;

/**
 * @method static \Intervention\Image\Image make(mixed $data)
 * @method static self configure(array $config)
 * @method static \Intervention\Image\Image canvas(int $width, int $height, mixed $background = null)
 * @method static \Intervention\Image\Image cache(\Closure $callback, int $lifetime = null, boolean $returnObj = false)
 */
class Image extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'image';
    }
}
