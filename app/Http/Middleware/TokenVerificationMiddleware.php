<?php

namespace App\Http\Middleware;

use Closure;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');
        $result = JWTToken::verifyTOken($token);

        if($result == 'unathorized'){
            return redirect('/user-login')->cookie('token', '', -1);
        } else{
            $request->headers->set('email',$result->email);
            // $request->headers->set('id',$result->userID);
            return $next($request);
        }
    }
}
