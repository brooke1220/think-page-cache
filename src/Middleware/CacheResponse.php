<?php

namespace Brooke\HttpCache\Middleware;

use Closure;
use Brooke\HttpCache\Cache;
use think\Request;
use think\Response;

class CacheResponse
{
    /**
     * The cache instance.
     *
     * @var \Silber\PageCache\Cache
     */
    protected $cache;

    /**
     * Constructor.
     *
     * @var \Silber\PageCache\Cache  $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if(config('oem.oeminfo.is_oem')) return $response;

        if ($this->shouldCache($request, $response)) {
            $this->cache->cache($request, $response);
        }

        return $response;
    }

    /**
     * Determines whether the given request/response pair should be cached.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return bool
     */
    protected function shouldCache(Request $request, Response $response)
    {
        return strtolower($request->method()) == 'get' && $response->getCode() == 200;
    }
}
