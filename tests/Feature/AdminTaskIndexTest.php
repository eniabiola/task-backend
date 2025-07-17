<?php

namespace Tests\Feature;

use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\TaskStatusSeeder;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskStatus;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTaskIndexTest extends TestCase
{
    use RefreshDatabase, WithFaker, RefreshDatabase;

    public function test_admin_can_view_all_tasks()
    {
        $this->seed(TaskStatusSeeder::class);
        $this->seed(RolePermissionSeeder::class);


        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $user1 = User::factory()->create();
        $user1->assignRole('user');
        $user2 = User::factory()->create();
        $user2->assignRole('user');

        $status = TaskStatus::query()->first();


        Task::factory()->create([
            'user_id' => $user1->id,
            'task_status_id' => $status->id,
        ]);
        Task::factory()->create([
            'user_id' => $user2->id,
            'task_status_id' => $status->id,
        ]);

        $this->actingAs($admin, 'sanctum');

        $response = $this->getJson('/api/tasks/admin/all');

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
                    ]
                ],
                'links',
                'meta'
            ]
        ]);

        $this->assertCount(2, $response->json('data.data'));
    }

    public function test_non_admin_cannot_access_admin_task_index()
    {
        $this->seed(RolePermissionSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('user');

        $this->actingAs($user, 'sanctum');

        $this->getJson('/api/tasks/admin/all')
            ->assertForbidden();
    }

    public function test_guest_cannot_access_admin_task_index()
    {
        $this->getJson('/api/tasks/admin/all')
            ->assertUnauthorized();
    }
}
