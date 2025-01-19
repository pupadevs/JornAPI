<?php

namespace App\Annotations\Swagger\Employee\RegisterEmployee;

use OpenApi\Annotations as OA;

final class RegisterEmployeeAnnotations
{
    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Register a new employee",
     *     description="Registers a new employee with the provided details.",
     *     tags={"Employee"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "normal_hourly_rate", "overtime_hourly_rate"},
     *
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *            @OA\Property(property="company_name", type="string", example="Company Inc."),
     *             @OA\Property(property="password", type="string", example="securepassword123"),
     *             @OA\Property(property="normal_hourly_rate", type="number", format="float", example=20.00),
     *             @OA\Property(property="overtime_hourly_rate", type="number", format="float", example=30.00),
     *             @OA\Property(property="night_hourly_rate", type="number", format="float", example=25.00),
     *             @OA\Property(property="holiday_hourly_rate", type="number", format="float", example=35.00),
     *             @OA\Property(property="irpf", type="number", format="float", example=5.00)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Employee created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Employee created successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=409,
     *         description="User already exists",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="error", type="string", example="User already exists")
     *         )
     *    ),
     *    @OA\Response(
     *     response=422,
     *     description="Unprocessable Content",
     *     @OA\JsonContent(
     *         @OA\Property(property="error", type="string", example="Validation failed")
     *        )
     *    )      
     * )
     */
    public function register() {}
}
