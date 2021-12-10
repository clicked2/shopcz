<?php

namespace app\http\middleware;
use think\facade\Cache;
class CheckTime
{
    public function handle($request, \Closure $next)
    {
    	if ( strtotime(Cache::get('checktime')) < strtotime(date("Y-m-d"))) {
    		Cache::clear();
    		Cache::set('checktime', date("Y-m-d"));
    	}
    	return $next($request);
    }
}
