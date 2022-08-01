<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Token;
use Illuminate\Http\Request;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $isNotExpired = json_decode(Token::viewToken($request->header('Authorization')));

        if ($isNotExpired->response != new \stdClass()) {
            return $next($request);
        } else {
            return $users = \Response::SetResponseApi(401, $isNotExpired->message, (object)array());
        }
    }
}
