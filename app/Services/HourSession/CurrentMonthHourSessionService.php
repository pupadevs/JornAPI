<?php

declare(strict_types=1);

namespace App\Services\HourSession;

use App\Models\HourSession;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class CurrentMonthHourSessionService
{
    /**
     * Summary of execute
     * @param string $empployeeId
     * @param Carbon $startMonth
     * @param Carbon $endMonth
     * @return Collection
     */
    public function execute(string $employeeId, Carbon $startMonth, Carbon $endMonth): Collection
    {
        if ($startMonth === null || $endMonth === null) {
            $startMonth = date('Y-m-01');
            $endMonth = date('Y-m-t');
        }
        $hourSessions = HourSession::where('employee_id', $employeeId)
            ->whereBetween('date', [$startMonth, $endMonth])->select('date', 'planned_hours', 'start_time', 'end_time', 'work_type')->get();

        return $hourSessions;
    }
}
