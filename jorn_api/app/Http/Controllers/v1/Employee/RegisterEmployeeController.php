<?php

namespace App\Http\Controllers\v1\Employee;

use App\Exceptions\UserAlReadyExists;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterEmployeeRequest;
use App\Services\Employee\RegisterEmployeeService;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterEmployeeController extends Controller
{

    public function __construct(private RegisterEmployeeService $service) {
        
    }
    public function __invoke(RegisterEmployeeRequest $request)
    {
        try{
            $this->service->execute($request->name,
                                     $request->email, 
                                     $request->password, 
                                     $request->normal_hourly_rate??0.00, 
                                     $request->overtime_hourly_rate??0.00,
                                     $request->night_hourly_rate??0.00, 
                                     $request->holiday_hourly_rate??0.00, 
                                     $request->irpf??0.00);
            return response()->json(['message' => 'Employee created successfully'], 201);
        }catch(UserAlReadyExists $e){
            throw new HttpResponseException(response()->json(['message' => $e->getMessage()], $e->getCode()));

        }
      
    }


}
