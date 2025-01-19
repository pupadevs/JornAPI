<?php

declare(strict_types=1);

namespace App\Services\Token;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TokenService
{
    /**
     * Summary of secret
     * @var string
     */
    protected string $secret;
    /**
     * Summary of ttl
     * @var int
     */
    protected int $ttl;

    public function __construct()
    {
        $this->secret = config('jwt.secret');
        $this->ttl = config('jwt.ttl');
    }
    
    /**
     * Summary of generateToken
     * @param string $userId
     * @return string
     */
    public function generateToken(string $userId)
    {
        $payload = [
            'sub' => $userId,
            'iat' => time(),
            'exp' => time() + ($this->ttl * 60), // Token válido por 30 minutos
            'jti' => bin2hex(random_bytes(16)), // Genera un ID único para el token
        ];

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    /**
     * Summary of decodeToken
     * @param string $token
     * @return \stdClass|null
     */
    public function decodeToken(string $token): ?object
    {
        try {
            $tokenDecoded = JWT::decode($token, new Key($this->secret, 'HS256'));

            return $tokenDecoded;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Summary of getJtiFromToken
     * @param string $token
     * @return mixed
     */
    public function getJtiFromToken(string $token): mixed
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));

            return $decoded->jti;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Summary of generateRefreshToken
     * @param string $userId
     * @return string
     */
    public function generateRefreshToken(string $userId): string
    {
        $refreshToken = Str::random(60); // Genera un refresh token aleatorio

        // Establece la fecha de expiración para 1 día a partir de ahora
        $expiresAt = Carbon::now()->addDay();

        // Guarda el refresh token en la base de datos
        DB::table('jwt_refresh_tokens')->insert([
            'id' => Str::uuid(),
            'user_id' => $userId,
            'refresh_token' => $refreshToken,
            'expires_at' => $expiresAt,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return $refreshToken;
    }

    /**
     * Summary of validateRefreshToken
     * @param string $userId
     * @param string $refreshToken
     * @return bool
     */
    public function validateRefreshToken(string $userId, string $refreshToken): bool
    {
        $record = DB::table('jwt_refresh_tokens')
            ->where('user_id', $userId)
            ->where('refresh_token', $refreshToken)
            ->first();

        if ($record && Carbon::now()->lessThanOrEqualTo($record->expires_at)) {
            return true;
        }

        return false;
    }

    /**
     * Summary of revokeRefreshToken
     * @param string $userId
     * @param string $refreshToken
     * @return void
     */
    public function revokeRefreshToken(string $userId, string $refreshToken): void
    {
        DB::table('jwt_refresh_tokens')
            ->where('user_id', $userId)
            ->where('refresh_token', $refreshToken)
            ->delete();
    }

    /**
     * Summary of revokeAllRefreshTokens
     * @param mixed $userId
     * @return void
     */
    public function revokeAllRefreshTokens($userId)
    {
        DB::table('jwt_refresh_tokens')
            ->where('user_id', $userId)
            ->delete();
    }
}
