<?php

namespace App\Annotations\Swagger\Employee\UpdateEmployee;

class UpdateEmployeeAnnotations
{
    /**
     * @OA\Put(
     *     path="/employee",
     *     summary="Update employee details",
     *     description="Updates the details of the employee associated with the authenticated user. The provided JWT token must belong to a user with the role of 'employee'.",
     *     tags={"Employee"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name", "company", "normal_hourly_rate", "overtime_hourly_rate"},
     *
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="company", type="string", example="Tech Corp"),
     *             @OA\Property(property="normal_hourly_rate", type="number", format="float", example=20.00),
     *             @OA\Property(property="overtime_hourly_rate", type="number", format="float", example=30.00),
     *             @OA\Property(property="night_hourly_rate", type="number", format="float", example=25.00),
     *             @OA\Property(property="holiday_hourly_rate", type="number", format="float", example=35.00),
     *             @OA\Property(property="irpf", type="number", format="float", example=5.00)
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Employee updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Employee updated successfully"),
     *             @OA\Property(property="employee", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="company", type="string", example="Tech Corp"),
     *                 @OA\Property(property="normal_hourly_rate", type="number", format="float", example=20.00),
     *                 @OA\Property(property="overtime_hourly_rate", type="number", format="float", example=30.00),
     *                 @OA\Property(property="night_hourly_rate", type="number", format="float", example=25.00),
     *                 @OA\Property(property="holiday_hourly_rate", type="number", format="float", example=35.00),
     *                 @OA\Property(property="irpf", type="number", format="float", example=5.00)
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Invalid input",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Unprocessable Content"),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function update() {}
}
