<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1\HourSession\UpdateHourSession;

use App\Exceptions\HourSessionNotFoundException;
use App\Exceptions\TimeEntryException;
use App\Http\Requests\UpdateHourSessionRequest;
use App\Services\HourSession\UpdateHourSessionService;
use Illuminate\Http\JsonResponse;

class UpdateHourSessionController
{
    /**
     * Summary of __construct
     * @param \App\Services\HourSession\UpdateHourSessionService $hourSessionUpdateService
     */
    public function __construct(private UpdateHourSessionService $hourSessionUpdateService) {}

    /**
     * Summary of __invoke
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(UpdateHourSessionRequest $request): JsonResponse
    {
        try {

            $employee = $request->user()->employee;

            $hourSession = $this->hourSessionUpdateService->execute(
                $employee->id,
                $request->query('date'),
                $request->start_time ?? null,
                $request->end_time ?? null,
                $request->planned_hours ?? null,
                $request->work_type);

            return response()->json(['message' => 'Hour worked updated successfully', 'HourSession' => $hourSession], 200);
        } catch (HourSessionNotFoundException|TimeEntryException $exception) {

            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
    }
}
