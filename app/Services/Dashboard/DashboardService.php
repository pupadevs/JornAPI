<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Exceptions\SalaryNotFoundException;
use App\Models\User;
use App\Services\Salary\FindSalaryByMonthService;
use App\Traits\TimeConverterTrait;
use Carbon\Carbon;

class DashboardService
{
    use TimeConverterTrait;

    /**
     * Summary of __construct
     */
    public function __construct(private FindSalaryByMonthService $findSalaryByMonthService) {}

    /**
     * Summary of execute
     * @param User $user
     * @return array
     */
    public function execute(User $user): array
    {

        try {
            $currentMonthSalary = $this->getCurrentMonth($user->employee->id);

            return [
                'total_hours_worked' => $currentMonthSalary['convertTotalHourWorked'],
                'current_month_salary' => $currentMonthSalary['currentMonthSalary'],
                //'current_month_hours_session' => $currentMonthHourSession
            ];
        } catch (SalaryNotFoundException $e) {

            return [
                'total_hours_worked' => 0,
                'current_month_salary' => 0,
                // 'current_month_hours_session' => []
            ];
        }

    }

    /**
     * Summary of getCurrentMonth
     *@param string $employeeId
     *@return array
     */
    private function getCurrentMonth(string $employeeId)
    {
        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');
        $startMonth = Carbon::create($year, (int) $month, 1);

        $currentMonthSalary = $this->findSalaryByMonthService->execute($employeeId, $startMonth->format('m'), $startMonth->format('Y'));

        $totalHoursWorked = $currentMonthSalary['total_normal_hours'] + $currentMonthSalary['total_overtime_hours'] + $currentMonthSalary['total_holiday_hours'];
        $totalHoursWorked = $this->convertDecimalToHoursAndMinutes($totalHoursWorked);
        $convertTotalHourWorked = ''.$totalHoursWorked['hours'].':'.$totalHoursWorked['minutes'];

        return [
            'convertTotalHourWorked' => $convertTotalHourWorked,
            'currentMonthSalary' => $currentMonthSalary['total_gross_salary'],
        ];
    }
}
