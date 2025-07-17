<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\TestHelperTrait;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase, TestHelperTrait;

    public function test_authenticated_user_can_get_task_statuses()
    {
        $this->authenticateUser();
        TaskStatus::factory()->count(3)->create();

        $response = $this->getJson('/api/task-statuses');

        $response->assertStatus(200);
        $responseData = $response->json('data');
        $this->assertCount(3, $responseData);
    }

    public function test_guest_cannot_access_task_statuses()
    {
        $response = $this->getJson('/api/task-statuses');

        $response->assertUnauthorized();
    }
}
