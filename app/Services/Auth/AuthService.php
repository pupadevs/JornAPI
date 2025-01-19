<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Exceptions\UserIsNotActiveException;
use App\Exceptions\UserNotFound;
use App\Models\User;
use App\Services\Token\TokenService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\UnauthorizedException;

class AuthService
{
    /**
     * Summary of __construct
     */
    public function __construct(private TokenService $jwtService) {}

    /**
     * Summary of execute
     *
     * @param string $email
     * @param string $password
     * @return array
     */
    public function execute(string $email, string $password): array
    {
        $user = $this->validateUser($email, $password);

        $tokens = $this->generateTokens($user);
       $cacheToken = $this->tokenExistsInRedis($user,$tokens);
       
        return $cacheToken ? ['token' => $cacheToken] : $tokens;
    }

    /**
     * Valida el usuario y sus credenciales.
     *@param string $email
     *@param string $password
     *@throws UserNotFound
     *@throws UnauthorizedException
     *
     */
    private function validateUser(string $email, string $password): User
    {
        $user = User::where('email', $email)->first();

        if (! $user) {
            throw new UserNotFound;
        }

        if (! $user->is_active) {
            throw new UserIsNotActiveException();
        }

        if (! Hash::check($password, $user->password)) {
            throw new UnauthorizedException('Invalid credentials.', 401);
        }

        return $user;
    }

    /**
     * Genera el token y el refresh token para el usuario.
     * @param User $user
     * @return string
     */
    private function generateTokens(User $user): string
    {
         return $this->jwtService->generateToken($user->id);

      
    }

    /**
     * Summary of RedisExists
     * @param User $user
     * @param string $token
     * @return mixed
     */

    private function tokenExistsInRedis(User $user,string  $token) {
        if(!Redis::exists("user:{$user->id}:token")) {
        Redis::set("user:{$user->id}:token", $token, 'EX', 3600);

        }
        return  Redis::get("user:{$user->id}:token");

      
    }
}
