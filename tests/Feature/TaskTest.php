<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test task creation with valid data.
     *
     * @return void
     */
    public function test_user_can_create_task_successfully()
    {
        $taskData = [
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'status' => 'pending',
            'due_date' => '2026-01-15',
        ];

        // Calls TaskController@store
        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'due_date',
                    'created_at',
                ],
            ]);
        // Verify the task is in the database
        $this->assertDatabaseHas('tasks', $taskData);
    }

    /**
     * Test task creation with invalid data.
     *
     * @return void
     */
    public function test_user_cannot_create_task_with_invalid_data()
    {
        $invalidData = [
            'title' => '', // required
            'status' => 'invalid_status', // not in enum
        ];

        // Calls TaskController@store
        $response = $this->postJson('/api/tasks', $invalidData);

        // Verify validation errors
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'status']);
    }

    /**
     * Test listing tasks.
     *
     * @return void
     */
    public function test_user_can_list_tasks()
    {
        Task::factory()->count(3)->create();

        // Calls TaskController@index
        $response = $this->getJson('/api/tasks');

        // Verify response structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'description',
                        'status',
                        'due_date',
                        'created_at',
                    ],
                ],
                'links',
                'meta',
            ]);
    }

    /**
     * Test updating a task.
     *
     * @return void
     */
    public function test_user_can_update_task()
    {
        // Create a task to update
        $task = Task::factory()->create([
            'title' => 'Original Title',
            'status' => 'pending',
        ]);

        // Calls TaskController@update
        $updateData = [
            'title' => 'Updated Title',
            'status' => 'completed',
        ];

        // Calls TaskController@update
        $response = $this->putJson("/api/tasks/{$task->id}", $updateData);

        // Verify response and database
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'due_date',
                    'created_at',
                ],
            ]);

        // Verify the task is updated in the database
        $this->assertDatabaseHas('tasks', array_merge(['id' => $task->id], $updateData));
    }

    /**
     * Test deleting a task.
     *
     * @return void
     */
    public function test_user_can_delete_task()
    {
        // Create a task to delete
        $task = Task::factory()->create();

        // Calls TaskController@destroy
        $response = $this->deleteJson("/api/tasks/{$task->id}");

        // Verify response and database
        $response->assertStatus(200)
            ->assertJson(['message' => 'Task deleted']);

        // Verify the task is soft deleted in the database
        $this->assertSoftDeleted($task);
    }
}
