<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\TaskStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminSingleUserTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_tasks_for_specific_user()
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(TaskStatusSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('admin',);

        $user = User::factory()->create();
        $user->assignRole('user');

        $status = TaskStatus::query()->first();

        Task::factory()->count(3)->create([
            'user_id' => $user->id,
            'task_status_id' => $status->id,
        ]);


        Task::factory()->count(2)->create([
            'user_id' => User::factory()->create()->id,
            'task_status_id' => $status->id,
        ]);

        $this->actingAs($admin, 'sanctum');

        $response = $this->getJson("/api/tasks/admin/user/{$user->id}");

        $response->assertOk();
        $response->assertJsonStructure([
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'due_date',
                        'status' ,
                        'task_status_id',
                        'status_badge_colour',
                        'created_at',
                        'created_by'
                    ],
                ],
                'links',
                'meta',
            ],
        ]);

        $tasks = $response->json('data.data');
        $this->assertCount(3, $tasks);
        foreach ($tasks as $task) {
            $this->assertEquals($user->id, $task['user_id']);
        }
    }

    public function test_non_admin_cannot_access_single_user_tasks()
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('user');

        $this->actingAs($user, 'sanctum');

        $targetUser = User::factory()->create();

        $this->getJson("/api/tasks/admin/user/{$targetUser->id}")
            ->assertForbidden();
    }

    public function test_guest_cannot_access_single_user_tasks()
    {
        $targetUser = User::factory()->create();

        $this->getJson("/api/tasks/admin/user/{$targetUser->id}")
            ->assertUnauthorized();
    }
}
