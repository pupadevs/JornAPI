<?php

declare(strict_types=1);

namespace App\Services\HourSession;

use App\DTO\HourSession\HourSessionDTO;
use App\Events\HourSessionRegistered;
use App\Exceptions\HourSessionExistException;
use App\Models\HourSession;
use App\Services\HourWorked\HourWorkedEntryService;
use App\Traits\ValidateTimeEntry;
use Illuminate\Support\Facades\DB;

class RegisterHourSessionService
{
    use ValidateTimeEntry;

    /**
     * @param HourWorkedEntryService $hourWorkedEntryService
     */
    public function __construct( private HourWorkedEntryService $hourWorkedEntryService,) {}

    /**
     * Summary of execute
     * @param string $employeeId
     * @param \App\DTO\HourSession\HourSessionDTO $hourSessionDTO
     * @throws \App\Exceptions\HourSessionExistException
     * @return void
     */
    public function execute(string $employeeId, HourSessionDTO $hourSessionDTO): void
    {
        // Validaciones
        $this->validateDateIsToday($hourSessionDTO->date);
        $this->validateTimeEntry($hourSessionDTO->startTime, $hourSessionDTO->endTime);

        // Verifica si ya existe una sesión de trabajo para el empleado en la misma fecha
        if ($this->sessionExists($employeeId, $hourSessionDTO->date)) {
            throw new HourSessionExistException;
        }

            // Crear la sesión de trabajo
           $this->createHourSession($hourSessionDTO, $employeeId);

        
    }
    /**
     * 
     * Verificar si ya existe una sesión de horas para un empleado en una fecha específica
     * @param string $employeeId
     * @param string $date
     * @return bool
     */
    protected function sessionExists(string $employeeId, string $date): bool
    {
        return HourSession::where('employee_id', $employeeId)
            ->where('date', $date)
            ->exists();
    }
    
    /**
     * Summary of createHourSession
     * @param \App\DTO\HourSession\HourSessionDTO $hourSessionDTO
     * @param string $employeeId
     * @return void
     */
    private function createHourSession(HourSessionDTO $hourSessionDTO, string $employeeId){
        DB::transaction(function () use ($employeeId, $hourSessionDTO) {
        $hourSession = HourSession::create([
            'employee_id' => $employeeId,
            'date' => $hourSessionDTO->date,
            'start_time' => $hourSessionDTO->startTime,
            'end_time' => $hourSessionDTO->endTime,
            'planned_hours' => $hourSessionDTO->plannedHours,
            'work_type' => $hourSessionDTO->workType  
        ]);

        // Ejecutar el servicio de HourWorkedEntry
     
            DB::afterCommit(function () use ($employeeId, $hourSession) {
                $this->hourWorkedEntryService->execute(
                    $hourSession->id,
                    $hourSession->start_time,
                    $hourSession->end_time,
                    $hourSession->planned_hours,
                    $hourSession->work_type);
                event(new HourSessionRegistered($employeeId, $hourSession->date));
               
            });

        });
    }

}
