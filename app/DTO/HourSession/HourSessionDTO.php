<?php

namespace App\DTO\HourSession;

use App\DTO\DTOInterface;
use Illuminate\Database\Eloquent\Model;

final readonly class HourSessionDTO implements DTOInterface
{
    public function __construct(
        public string $date,
        public string $startTime,
        public string $endTime,
        public int $plannedHours,
        public string $workType

    ) {}

    public static function fromModel(Model $hourSession): self
    {
        return new self(
            $hourSession->date,
            $hourSession->start_time,
            $hourSession->end_time,
            $hourSession->planned_hours,
            $hourSession->work_type
        );
    }

    public static function toArray(array $data)
    {
        return [
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'planned_hours' => $data['planned_hours'],
            'work_type' => $data['work_type'],
        ];
    }
}
