<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    // TEST 1: Listar tareas (GET /api/tasks -> 200 OK)
    public function test_puede_listar_todas_las_tareas(): void
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data'); 
    }

    // TEST 2: Crear tarea con datos válidos (POST /api/tasks -> 201 Created)
    public function test_puede_crear_una_nueva_tarea_con_datos_validos(): void
    {
        $user = User::factory()->create();

        $payload = [
            'title' => 'Terminar pruebas PHPUnit en Laravel 13',
            'description' => 'Escribir 5 tests para cubrir el CRUD completo.',
            'status' => 'pending',
            'assignee' => $user->id,
            'due_date' => '2026-12-01',
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Terminar pruebas PHPUnit en Laravel 13']);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Terminar pruebas PHPUnit en Laravel 13',
            'assignee' => $user->id,
        ]);
    }

    // TEST 3: Error de validación al enviar datos inválidos (POST /api/tasks -> 422)
    public function test_falla_al_crear_tarea_con_datos_invalidos_y_devuelve_422(): void
    {
        $response = $this->postJson('/api/tasks', [
            'title' => '', 
            'due_date' => 'no-es-una-fecha', 
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'due_date']);
    }

    // TEST 4: Actualizar una tarea existente (PUT /api/tasks/{id} -> 200 OK)
    public function test_puede_actualizar_una_tarea_existente(): void
    {
        $task = Task::factory()->create(['title' => 'Titulo original']);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Titulo actualizado',
            'description' => $task->description,
            'status' => 'done',
            'assignee' => $task->assignee,
            'due_date' => $task->due_date,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Titulo actualizado',
            'status' => 'done',
        ]);
    }

    // TEST 5: Eliminar una tarea (DELETE /api/tasks/{id} -> 204 No Content)
    public function test_puede_eliminar_una_tarea_y_devuelve_204(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
