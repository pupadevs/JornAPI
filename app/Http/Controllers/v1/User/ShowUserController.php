<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1\User;

use App\DTO\User\UserDTO;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowUserController extends Controller
{
   

    /**
     * Summary of __invoke
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {

        $user = $request->user();

        return response()->json(['message' => 'User found successfully', 'user' => UserDTO::toArray($user->toArray())], 200);

    }
}
