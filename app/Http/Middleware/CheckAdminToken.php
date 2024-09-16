<?php

namespace App\Http\Middleware;

use App\Traits\GeneralTrait;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class CheckAdminToken
{

    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $error) {
            if ($error instanceof TokenExpiredException) {
                return response()->json(['error' => 'The token has been expired!'], 401);
            } elseif ($error instanceof TokenInvalidException) {
                return response()->json(['error' => 'The token is invalid!'], 498);
            } else {
                return response()->json(['error' => 'The token does not exist'], 404);
            }
        }

        return $next($request);
    }
}
