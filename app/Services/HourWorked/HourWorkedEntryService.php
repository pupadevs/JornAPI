<?php

declare(strict_types=1);

namespace App\Services\HourWorked;

use App\Models\HourWorked;
use App\Traits\ValidateTimeEntry;
use Illuminate\Support\Facades\DB;

class HourWorkedEntryService
{
    use CalculateTrait;
    use ValidateTimeEntry;

    /**
     * Summary of execute
     * @param string $hourSessionId
     * @param string $startTime
     * @param string $endTime
     * @param int $plannedHours
     * @param string $workType
     * @return void
     */
    public function execute(string $hourSessionId, string $startTime, string $endTime, int $plannedHours, string $workType): void
    {

        // Validar la entrada de tiempo
        $this->validateTimeEntry($startTime, $endTime);

        $hoursList = $this->calculate($startTime, $endTime, $plannedHours, $workType);

        DB::transaction(function () use ($hourSessionId, $hoursList) {
            HourWorked::create([
                'hour_session_id' => $hourSessionId,
                'normal_hours' => $hoursList['normalHours'],
                'overtime_hours' => $hoursList['overtimeHours'],
                'holiday_hours' => $hoursList['holidayHours'],
            ]);
        });

    }
}
