<?php

declare(strict_types=1);

namespace App\Services\Salary;

use App\Models\Employee;
use App\Traits\TimeConverterTrait;

trait CalculateSalaryTrait
{
    use TimeConverterTrait;

    /**
     * Summary of calculateSalary
     */
    public function calculateSalary(mixed $hoursWorkeds, Employee $employee): array
    {
        $hourCalculations = $this->calculateTotalHoursWorked($hoursWorkeds);
        $convertToNormalHours = $this->convertDecimalToHoursAndMinutes($hourCalculations['total_normal_hours']);
        $convertToOvertimeHours = $this->convertDecimalToHoursAndMinutes($hourCalculations['total_overtime_hours']);
        $convertToHolidayHours = $this->convertDecimalToHoursAndMinutes($hourCalculations['total_holiday_hours']);

        $grossSalary = $this->calculateGrossSalary(
            $convertToNormalHours,
            $convertToOvertimeHours,
            $convertToHolidayHours,
            $employee);

        return [
            'total_normal_hours' => $hourCalculations['total_normal_hours'],
            'total_overtime_hours' => $hourCalculations['total_overtime_hours'],
            'total_holiday_hours' => $hourCalculations['total_holiday_hours'],
            'gross_salary' => $grossSalary,
        ];
    }

    /**
     * Summary of calculateTotalHoursWorked
     */
    private function calculateTotalHoursWorked(mixed $hoursWorkeds): array
    {
        $totalNormalHours = $hoursWorkeds->sum('normal_hours');
        $totalOvertimeHours = $hoursWorkeds->sum('overtime_hours');
        $totalHolidayHours = $hoursWorkeds->sum('holiday_hours');

        return [
            'total_normal_hours' => $totalNormalHours,
            'total_overtime_hours' => $totalOvertimeHours,
            'total_holiday_hours' => $totalHolidayHours,

        ];
    }

    /**
     * Summary of calculateGrossSalary
     */
    private function calculateGrossSalary(array $totalNormalHours, array $totalOvertimeHours, array $totalHolidayHours, Employee $employee): float
    {
        // Convierte los minutos a fracciones de hora
        $normalHoursWithMinutes = $totalNormalHours['hours'] + ($totalNormalHours['minutes'] / 60);
        $overtimeHoursWithMinutes = $totalOvertimeHours['hours'] + ($totalOvertimeHours['minutes'] / 60);
        $holidayHoursWithMinutes = $totalHolidayHours['hours'] + ($totalHolidayHours['minutes'] / 60);

        // Calcula el salario bruto tomando en cuenta horas y minutos
        $totalNormalSalary = $normalHoursWithMinutes * $employee->normal_hourly_rate;
        $totalOvertimeSalary = $overtimeHoursWithMinutes * $employee->overtime_hourly_rate;
        $totalHolidaySalary = $holidayHoursWithMinutes * $employee->holiday_hourly_rate;

        // Suma todos los salarios
        return $totalNormalSalary + $totalOvertimeSalary + $totalHolidaySalary;
    }
}
