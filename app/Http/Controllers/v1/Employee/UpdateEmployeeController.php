<?php

declare(strict_types=1);

namespace App\Http\Controllers\v1\Employee;

use App\Exceptions\UserNotFound;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Services\Employee\UpdateEmployeeService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UpdateEmployeeController extends Controller
{
    /**
     * Summary of __construct
     */
    public function __construct(private UpdateEmployeeService $employeeUpdateService) {}

    /**
     * Summary of __invoke
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function __invoke(UpdateEmployeeRequest $request): JsonResponse
    {
        try {
            $user = $request->user();
            $employee = $this->employeeUpdateService->execute($request->name ?? null,
                $request->company_name ?? null,
                $request->normal_hourly_rate ?? null,
                $request->overtime_hourly_rate ?? null,
                $request->holiday_hourly_rate ?? null,
                $request->irpf ?? null,
                $user->employee);

            return response()->json(['message' => 'Employee updated successfully', 'employee' => $employee], 200);
        } catch (UserNotFound $e) {
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));
        }

    }
}
