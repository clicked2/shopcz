<?
namespace app\http\middleware;

class Filter
{
	//过滤js标签
	public function handle($request, \Closure $next)
	{
		// $param = $request->param();
		// foreach ( $param as $key => $value ) {
		// 	if (preg_match('/<script>/i', $value)) 
		// 		$request->$key = str_replace('</script>', '&lt;/script&gt;', str_replace('<script>', '&lt;script&gt;', $value));
		// }
		return $next($request);
	}
}
