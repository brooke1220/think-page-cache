<?php

declare(strict_types=1);

namespace Brooke\HttpCache;

use think\App;
use think\Request;
use Brooke\Supports\ServiceProvider;

class HttpCacheProvider extends ServiceProvider
{
    public static function register(App $app, Request $request)
    {
        $app->bindTo('files', Filesystem::class);

        $provider = static::getInstance();

        $provider->addCommands([
            'http-cache:clear' => Console\ClearCache::class
        ]);

        $app->bindTo(Cache::class, function () use ($app) {
            $instance = new Cache($app->make('files'));
            return $instance->setContainer($app);
        });

        $app->middleware->setConfig([
            'http.cache' => [
                Middleware\CacheResponse::class
            ],
        ]);
    }
}
