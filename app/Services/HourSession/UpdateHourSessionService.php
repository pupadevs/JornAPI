<?php

declare(strict_types=1);

namespace App\Services\HourSession;

use App\DTO\HourSession\HourSessionDTO;
use App\Enums\WorkTypeEnum;
use App\Events\HourSessionUpdatedEvent;
use App\Exceptions\HourSessionNotFoundException;
use App\Models\HourSession;
use App\Services\HourWorked\HourWorkedUpdateService;
use App\Traits\ValidateTimeEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateHourSessionService
{
    use ValidateTimeEntry;
    /**
     * Summary of __construct
     * @param \App\Services\HourWorked\HourWorkedUpdateService $hourWorkedUpdateService
     */
    public function __construct(private HourWorkedUpdateService $hourWorkedUpdateService) {}
    /**
     * Summary of execute
     * @param string $employeeId
     * @param mixed $date
     * @param ?string $startTime
     * @param mixed $endTime
     * @param mixed $plannedHours
     * @param mixed $workType
     * @return array
     */
    public function execute(string $employeeId, ?string $date, ?string $startTime, ?string $endTime, ?int $plannedHours,?string $workType): array
    {
        $carbon = new Carbon($date);

        $hourSession = $this->HourSessionExists($employeeId, $carbon);
         $this->validateDateIsToday($date);
       

        $this->validateTimeEntry($startTime, $endTime);

        DB::transaction(function () use ($employeeId, $startTime, $endTime, $plannedHours, $workType, $hourSession, $date) {

           $this->insertDataToFields($hourSession,$startTime,$endTime,$plannedHours,$workType);

            DB::afterCommit(function () use ($employeeId, $date, $hourSession, $workType) {
            $this->hourWorkedUpdateService->execute($hourSession->id, (string)$hourSession->start_time, (string)$hourSession->end_time, $hourSession->planned_hours, $workType);

                event(new HourSessionUpdatedEvent($employeeId, $date));

            });
        });

        return HourSessionDTO::toArray($hourSession->toArray());
    }
    /**
     * Summary of HourSessionExists
     * @param string $employeeId
     * @param \Carbon\Carbon $carbon
     * @throws \App\Exceptions\HourSessionNotFoundException
     * @return \App\Models\HourSession
     */
    private function HourSessionExists(string $employeeId, Carbon $carbon): HourSession{
        $hourSession = HourSession::where('employee_id', $employeeId)->where('date', $carbon->format('Y-m-d'))->first();
        if (! $hourSession) {

            throw new HourSessionNotFoundException;
        }
        return $hourSession;
    }
    /**
     * Summary of insertDataToFields
     * @param \App\Models\HourSession $hourSession
     * @param string $startTime
     * @param string $endTime
     * @param int $plannedHours
     * @param string $workType
     * @return void
     */
    private function insertDataToFields(HourSession $hourSession, string $startTime, string $endTime, int $plannedHours, ?string $workType){
        if ($startTime != null) {
            $hourSession->start_time = $startTime;
        }
        if ($endTime != null) {
            $hourSession->end_time = $endTime;
        }
        if ($plannedHours != null) {
            $hourSession->planned_hours = $plannedHours;
        }
        if ($workType != null) {
            $hourSession->work_type = WorkTypeEnum::fromValue($workType)->value;
        }
       
        $hourSession->save();
    }
}
