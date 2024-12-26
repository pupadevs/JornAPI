<?php
namespace Tests\Feature\User\ShowUser;

use App\Models\Employee;
use App\Models\User;
use App\Services\Token\TokenService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;


class ShowUserNotFoundTest extends TestCase
{
    use DatabaseTransactions;
    private TokenService $tokenService;
    private Employee $employee; 

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenService = new TokenService();
        $this->employee = Employee::factory()->create();
    }

    public function testShowUserNotFound()
    {
        $uuid = '12345678-1234-1234-1234-123456789012';
        $token =$this->tokenService->generateToken($uuid,$this->employee->user->roles);
        Cache::store('redis')->put("user:{$uuid}:token", $token, 3600); // 

        $showUser = $this->withHeaders([
    'Authorization' => 'Bearer ' . $token,
])->getJson('/api/user/show');

        $showUser->assertStatus(404);

        $showUser->assertJson([
            'message' => 'User not found',

        ]);
    }

}