<?php

declare(strict_types=1);

namespace App\Services\Employee;

use App\DTO\Employee\RegisterEmployeeDTO;
use App\DTO\Employee\ShowEmployeeDTO;
use App\Exceptions\UserNotFound;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class UpdateEmployeeService
{
    /**
     * Summary of execute
     *
     * @param  mixed  $name
     * @param  mixed  $company
     * @param  mixed  $normalHourlyRate
     * @param  mixed  $overtimeHourlyRate
     * @param  mixed  $holidayHourlyRate
     * @param  mixed  $irpf
     * @param  mixed  $uuid
     * @return array
     *
     * @throws \App\Exceptions\UserNotFound
     */
    public function execute(?string $name,
        ?string $company,
        ?float $normalHourlyRate,
        ?float $overtimeHourlyRate,
        ?float $holidayHourlyRate,
        ?float $irpf,
        Employee $employee): array
    {
       

        DB::transaction(function () use ($employee, $name, $company, $normalHourlyRate, $overtimeHourlyRate, $holidayHourlyRate, $irpf) {

            if ($name != null) {
                $employee->name = $name;
            }

            if ($company != null) {
                $employee->company_name = $company;
            }

            if ($normalHourlyRate != null) {
                $employee->normal_hourly_rate = $normalHourlyRate;
            }

            if ($overtimeHourlyRate != null) {
                $employee->overtime_hourly_rate = $overtimeHourlyRate;
            }

            if ($holidayHourlyRate != null) {
                $employee->holiday_hourly_rate = $holidayHourlyRate;
            }

            if ($irpf != null) {
                $employee->irpf = $irpf;
            }

            $employee->save();
        });

        return ShowEmployeeDTO::toArray($employee->toArray());
    }
}
