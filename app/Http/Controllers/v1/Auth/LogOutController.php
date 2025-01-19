<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1\Auth;

use App\Exceptions\InvalidTokenException;
use App\Http\Controllers\Controller;
use App\Services\Auth\LogOutService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Summary of LogOutController
 */
class LogOutController extends Controller
{
    /**
     * Summary of __construct
     * @param LogOutService $logOutService
     */
    public function __construct(private LogOutService $logOutService) {}

    /**
     * Summary of __invoke
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     *
     * @throws \App\Exceptions\InvalidTokenException
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function __invoke(Request $request): JsonResponse
    {

        try {
            $this->logOutService->logOut($request->bearerToken());

            return response()->json(['message' => 'Logged out successfully']);

        } catch (InvalidTokenException $e) {
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));
        }

    }
}
