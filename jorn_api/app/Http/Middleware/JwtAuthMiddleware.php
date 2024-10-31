<?php

namespace App\Http\Middleware;

use App\Exceptions\InvalidTokenException;
use App\Exceptions\TokenNotProvidedException;
use App\Exceptions\UserNotFound;
use Closure;
use Illuminate\Http\Request;
use App\Services\Token\TokenService;
use Illuminate\Http\Exceptions\HttpResponseException;

class JwtAuthMiddleware
{
    protected $jwtService;

    public function __construct(TokenService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            $token = $request->bearerToken();

            if (!$token) {
                throw new TokenNotProvidedException();
            }
    
            $decoded = $this->jwtService->decodeToken($token);
    
            if (!$decoded) {
                throw new InvalidTokenException();
            }
    
            $user = \App\Models\User::find($decoded->sub);
            if (!$user) {
                throw new UserNotFound();
            }
            //auth()->setUser($user);
         
            $request->attributes->set('token', $token);
            $request->attributes->set('user', $user);
    
            return $next($request);

        }catch(InvalidTokenException | TokenNotProvidedException | UserNotFound  $e){
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));
        }
       
    }
}