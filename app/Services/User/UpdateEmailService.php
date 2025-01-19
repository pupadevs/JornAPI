<?php

declare(strict_types=1);

namespace App\Services\User;

use App\DTO\User\UserDTO;
use App\Exceptions\UserNotFound;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateEmailService
{
    /**
     * Summary of execute
     * @param mixed $email
     * @param mixed $uuid
     * @throws \App\Exceptions\UserNotFound
     * @return \App\Models\User
     */
    public function execute(?string $email, string $uuid): UserDTO
    {

        $user = User::where('id', $uuid)->first();
        if (! $user) {
            throw new UserNotFound;
        }
        DB::transaction(function () use ($user, $email) {
                $user->email = $email;
            $user->save();
        });

        return UserDTO::fromModel($user);

    }
}
