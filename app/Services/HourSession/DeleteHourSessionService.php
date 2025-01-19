<?php

declare(strict_types=1);

namespace App\Services\HourSession;

use App\Events\HourSessionUpdatedEvent;
use App\Exceptions\HourSessionNotFoundException;
use App\Models\HourSession;
use Illuminate\Support\Facades\DB;

class DeleteHourSessionService
{
    /**
     * Summary of execute
     * @param string  $employeeId
     * @param string $date
     *
     * @throws \App\Exceptions\HourSessionNotFoundException
     */
    public function execute(string $employeeId, string $date): void
    {
        $hourSession = HourSession::where('employee_id', $employeeId)->where('date', $date)->first();
        if (! $hourSession) {
            throw new HourSessionNotFoundException;
        }
        DB::transaction(function () use ($hourSession, $employeeId, $date) {
            $hourSession->delete();

            DB::afterCommit(function () use ($employeeId, $date) {
                event(new HourSessionUpdatedEvent($employeeId, $date));
            });
        });

    }
}
