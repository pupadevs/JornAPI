<?php

declare(strict_types=1);

namespace App\Services\Salary;

use App\Models\Employee;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalaryService implements SalaryServiceInterface
{
    use CalculateSalaryTrait;

    /**
     * Summary of execute
     * @param string $employeeId
     * @param string $date
     * @return void
     */
    public function execute(string $employeeId, string $date): void
    {
        $date = new Carbon($date);
        // Primer día del mes
        $startOfMonth = new Carbon($date->copy()->startOfMonth()->toDateString());

        // Último día del mes
        $endOfMonth = new Carbon($date->copy()->endOfMonth()->toDateString()); // Último día del mes

        $employee = Employee::findOrFail($employeeId);

        

        // Obtenemos todas las HourSession en el rango y pluck para obtener solo los IDs
        $hourSessions = $this->prepareHourSession($employee, $startOfMonth, $endOfMonth);

        $salary = $this->prepateSalary($employeeId,$startOfMonth,$endOfMonth);

        // Agrupar los datos en una colección
        $hourWorkedCollection = $hourSessions->pluck('hourWorked')->select('total_normal_hours','total_overtime_hours','total_holiday_hours');
        $dataSalary = $this->calculateSalary($hourWorkedCollection, $employee);

        // Ahora puedes usar la colección $hourWorkeds para cálculos adicionales o sumar sus valores
        if ($salary) {
            DB::transaction(function () use ($salary, $dataSalary) {
                $salary->total_normal_hours = $dataSalary['total_normal_hours'];

                $salary->total_overtime_hours = $dataSalary['total_overtime_hours'];

                $salary->total_holiday_hours = $dataSalary['total_holiday_hours'];

                $salary->total_gross_salary = $dataSalary['gross_salary'];

                $salary->save();
            });

        } else {
            DB::transaction(function () use ($employeeId, $startOfMonth, $endOfMonth, $dataSalary) {
                $salary = Salary::create(
                    ['employee_id' => $employeeId,
                        'start_date' => $startOfMonth,
                        'end_date' => $endOfMonth,
                        'total_normal_hours' => $dataSalary['total_normal_hours'],
                        'total_overtime_hours' => $dataSalary['total_overtime_hours'],
                        'total_holiday_hours' => $dataSalary['total_holiday_hours'],
                        'total_gross_salary' => $dataSalary['gross_salary'],
                        'total_net_salary' => 0,

                    ]);
            });

        }

    }

    /**
     * Summary of prepareHourSession
     * @param \App\Models\Employee $employee
     * @param mixed $startOfMonth
     * @param mixed $endOfMonth
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function prepareHourSession(Employee $employee, $startOfMonth, $endOfMonth){
      return  $employee->hourSessions()
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->with('hourWorked')
        ->get();
    }
    /**
     * Summary of prepateSalary
     * @param string $employeeId
     * @param mixed $startOfMonth
     * @param mixed $endOfMonth
     * @return Salary|null
     */
    private function prepateSalary(string $employeeId, $startOfMonth, $endOfMonth): Salary|null{
      return  Salary::where('employee_id', $employeeId)
        ->whereBetween('start_date', [$startOfMonth, $endOfMonth])
        ->whereBetween('end_date', [$startOfMonth, $endOfMonth])
        ->first();
    }
}