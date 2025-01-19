<?php 
declare(strict_types=1);

namespace App\DTO\Employee;

use App\DTO\DTOInterface;
use Illuminate\Database\Eloquent\Model;

 final readonly class ShowEmployeeDTO implements DTOInterface{

    public function __construct(
        public string $name,
        public string $company_name,
        public string $normal_hourly_rate,
        public string $overtime_hourly_rate,
        public string $holiday_hourly_rate,
        public ?string $irpf
    ){}
    public static function toArray(array $data): array
    {
        return [
            'name' => $data['name'],
            'company_name' => $data['company_name'],
            'normal_hourly_rate' => $data['normal_hourly_rate'],
            'overtime_hourly_rate' => $data['overtime_hourly_rate'],
            'holiday_hourly_rate' => $data['holiday_hourly_rate'],
            'irpf' => $data['irpf'],
        ];
    }

    public static function fromModel(Model $employee): self
    {
        return new self(
            $employee->name,
            $employee->company_name,
            $employee->normal_hourly_rate,
            $employee->overtime_hourly_rate,
            $employee->holiday_hourly_rate,
            $employee->irpf,
        );
    }

 }