<?php

declare(strict_types=1);

namespace App\DTO\User;

use App\DTO\DTOInterface;
use Illuminate\Database\Eloquent\Model;

final readonly class RegisterUserDTO implements DTOInterface
{
    public function __construct(
        public string $email,
        public string $password

    ) {}

    public static function fromModel(Model $object): RegisterUserDTO
    {

        return new self(
            $object->email,
            $object->password

        );

    }

    public static function toArray(array $data)
    {

        return [
            'email' => $data['email'],
            'password' => $data['password']
        ];
    }
}
