<?php
declare(strict_types=1);

namespace App\Services\Employee;

use App\Exceptions\UserNotFound;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class EmployeeUpdateService{
    /**
     * Summary of execute
     * @param mixed $name
     * @param mixed $company
     * @param mixed $normalHourlyRate
     * @param mixed $overtimeHourlyRate
     * @param mixed $nightHourlyRate
     * @param mixed $holidayHourlyRate
     * @param mixed $irpf
     * @param mixed $uuid
     * @throws \App\Exceptions\UserNotFound
     * @return \App\Models\Employee
     */
    public function execute(?string $name, 
                            ?string $company, 
                            ?float $normalHourlyRate, 
                            ?float $overtimeHourlyRate, 
                            ?float $holidayHourlyRate, 
                            ?float $irpf, 
                            ?string $uuid): Employee{
        $employee = Employee::where('user_id', $uuid)->select('name', 'company', 'normal_hourly_rate', 'overtime_hourly_rate', 'night_hourly_rate', 'holiday_hourly_rate', 'irpf')->first();
        if(!$employee){
            throw new UserNotFound();
        }

        DB::transaction(function () use ($employee, $name, $company, $normalHourlyRate, $overtimeHourlyRate, $holidayHourlyRate, $irpf) {
          
            if($name != null){
                $employee->name = $name;
            }
    
            if($company != null){
                $employee->company_name = $company;
            }
    
            if($normalHourlyRate != null){
                $employee->normal_hourly_rate = $normalHourlyRate;
            }
    
            if($overtimeHourlyRate != null){
                $employee->overtime_hourly_rate = $overtimeHourlyRate;
            }
    
    
            if($holidayHourlyRate != null){
                $employee->holiday_hourly_rate = $holidayHourlyRate;
            }
    
            if($irpf != null){
                $employee->irpf = $irpf;
            }
        
            $employee->save();
        });
       
        return $employee;
    }
}