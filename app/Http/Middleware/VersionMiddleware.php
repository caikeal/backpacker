<?php

namespace App\Http\Middleware;

use Closure;

class VersionMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        if(!$request->header('X-Api-Version')) {
            $err = ["message" => "头信息有误", "status_code" => 406,];
            return response()->json($err)->setStatusCode(406);
        }
        return $next($request);
    }
}
