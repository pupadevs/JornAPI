<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEmailRequest;
use App\Services\User\UpdateEmailService;

class UpdateEmailController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\User\UpdateEmailService $userUpdateService
     */
    public function __construct(private UpdateEmailService $userUpdateService) {}

    /**
     * Summary of __invoke
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateEmailRequest $request)
    {

        $user = $request->user();
        $user = $this->userUpdateService->execute($request->email, $user->id);

        return response()->json(['message' => 'Email updated successfully', 'user' => $user], 200);
    }
}
