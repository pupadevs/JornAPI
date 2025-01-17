<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1\HourSession\DeleteHourSession;

use App\Exceptions\HourSessionNotFoundException;
use App\Http\Controllers\Controller;
use App\Services\HourSession\DeleteHourSessionService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeleteHourSessionController extends Controller
{
    /**
     * Summary of __construct
     */
    public function __construct(private DeleteHourSessionService $hourSessionDeleteService) {}

    /**
     * Summary of __invoke
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $query = $request->query('date');
            $employee = $request->user()->employee;
            $this->hourSessionDeleteService->execute($employee->id, $query);

            return response()->json(['message' => 'Hour worked deleted successfully'], 200);

        } catch (HourSessionNotFoundException $exception) {
            throw new HttpResponseException(response()->json(['message' => $exception->getMessage()], $exception->getCode()));
        }

    }
}
