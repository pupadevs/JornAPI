<?php
declare(strict_types=1);
namespace App\Http\Controllers\v1\User;

use App\Exceptions\ChangePassWordException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Services\User\ChangePasswordService;

class ChangePasswordController extends Controller
{
    /**
     * Summary of __construct
     * @param \App\Services\User\ChangePasswordService $service
     */
    public function __construct(private ChangePasswordService $service){}
    /**
     * Summary of __invoke
     * @param \App\Http\Requests\ChangePasswordRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function __invoke(ChangePasswordRequest $request){
        try {
            $this->service->execute($request->user(), $request->old_password, $request->new_password);
            return response()->json(['message' => 'Password changed successfully'], 200);
        } catch (ChangePassWordException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
        
    }
}
