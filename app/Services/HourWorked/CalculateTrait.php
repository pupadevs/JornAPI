<?php

declare(strict_types=1);

namespace App\Services\HourWorked;

use App\Exceptions\TimeEntryException;
use Carbon\Carbon;

trait CalculateTrait
{
    use HourCalculateTrait;

    /**
     * Summary of calculate
     *
     * @param  mixed  $startTime
     * @param  mixed  $endTime
     * @param  mixed  $plannedHours
     * @param  mixed  $isHoliday
     * @param  mixed  $isOvertime
     */
    private function calculate($startTime, $endTime, $plannedHours, ?string $workType): array
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        $hoursWorkedCalculated = $this->verifyDuration($start, $end);
        // Verificar si es festivo y calcular las horas festivas
        $holidayHours = $this->calculateHolidayHours($hoursWorkedCalculated, $workType);
        // Calcular horas extras
        $regularOvertimeHours = $this->calculateRegularOvertimeHours($hoursWorkedCalculated, $plannedHours, $workType);
       
        // Calcular dia complementario
        $extraShiftHours = $this->calculateExtraShiftOvertime($hoursWorkedCalculated, $workType);
        // Calcular las horas normales
        $normalHours = $this->calculateNormalHours(
            $hoursWorkedCalculated,
            $regularOvertimeHours,
            $workType);

        return [
            'normalHours' => $normalHours,
            'overtimeHours' => $regularOvertimeHours + $extraShiftHours,
            'holidayHours' => $holidayHours,
        ];
    }
    /**
     * Summary of verifyDuration
     * @param mixed $start
     * @param mixed $end
     * @throws \App\Exceptions\TimeEntryException
     * @return mixed
     */
    private function verifyDuration($start, $end)
    {
        $maxHoursWorked = 12;
        $minHoursWorked = 2;
        if ($end < $start) {
            // Añadir un día a la hora de fin
            throw new TimeEntryException('The start time cannot be greater than the end time');
        }

        if ($end <= $start || $start > $end) {
            throw new TimeEntryException('The start time cannot be greater than the end time');
        }
        $hoursWorkedCalculated = $start->floatDiffInHours($end);

        if ($hoursWorkedCalculated >= $maxHoursWorked || $hoursWorkedCalculated < $minHoursWorked) {
            throw new TimeEntryException(
                "The hours worked must be between {$minHoursWorked} and {$maxHoursWorked}. You provided {$hoursWorkedCalculated}."
            );
        }

        return $hoursWorkedCalculated;
    }
}
