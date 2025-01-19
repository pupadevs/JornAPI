<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Exceptions\NullDataException;
use App\Exceptions\UserAlreadyExists;
use App\Jobs\SendRegisterNotification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterUserService
{
    /**
     * Summary of execute
     * @param string $email
     * @param string $password
     * @throws \App\Exceptions\NullDataException
     * @throws \App\Exceptions\UserAlreadyExists
     * @return \App\Models\User
     */
    public function execute(string $email, string $password): User
    {
    
        $user = User::where('email', $email)->first();
        if ($user) {
            throw new UserAlreadyExists;
        }
        $user = DB::transaction(function () use ($email, $password) {
            $data = User::create([
                'email' => $email,
                'password' => Hash::make($password),
            ]);

            return $data;
        });

        return $user;
    }
}
