<?php
declare(strict_types=1);
namespace App\Services\Salary;

interface SalaryServiceInterface
{
    /**
     * Summary of execute
     * @param string $employeeId
     * @param string $date
     * @return void
     */
    public function execute(string $employeeId, string $date);
}
