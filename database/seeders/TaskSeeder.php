<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::query()->delete();

        $userIds = User::query()->pluck('id')->all();

        $tasks = [
            [
                'title' => 'Preparar informe mensual',
                'description' => 'Reunir datos de ventas y preparar el informe para dirección.',
                'status' => 'pending',
                'due_date' => now()->addDays(3)->toDateString(),
            ],
            [
                'title' => 'Revisar errores del sistema',
                'description' => 'Investigar los errores reportados por los usuarios.',
                'status' => 'in_progress',
                'due_date' => now()->addDays(1)->toDateString(),
            ],
            [
                'title' => 'Actualizar documentación',
                'description' => 'Documentar los endpoints de la API de tareas.',
                'status' => 'pending',
                'due_date' => now()->addDays(5)->toDateString(),
            ],
            [
                'title' => 'Diseñar pantalla de tareas',
                'description' => 'Crear el diseño de la interfaz para listar y editar tareas.',
                'status' => 'in_progress',
                'due_date' => now()->addDays(7)->toDateString(),
            ],
            [
                'title' => 'Configurar base de datos',
                'description' => 'Crear migraciones, índices y configuración inicial.',
                'status' => 'done',
                'due_date' => now()->subDays(2)->toDateString(),
            ],
            [
                'title' => 'Crear pruebas automatizadas',
                'description' => 'Escribir pruebas para crear, listar, actualizar y eliminar tareas.',
                'status' => 'done',
                'due_date' => now()->subDay()->toDateString(),
            ],
            [
                'title' => 'Revisar seguridad de la API',
                'description' => 'Validar permisos, validaciones y posibles vulnerabilidades.',
                'status' => 'pending',
                'due_date' => now()->addDays(10)->toDateString(),
            ],
            [
                'title' => 'Optimizar consultas',
                'description' => 'Revisar índices y consultas lentas en el listado de tareas.',
                'status' => 'in_progress',
                'due_date' => now()->addDays(14)->toDateString(),
            ],
            [
                'title' => 'Publicar primera versión',
                'description' => 'Preparar despliegue y verificar la API en producción.',
                'status' => 'pending',
                'due_date' => now()->addDays(20)->toDateString(),
            ],
            [
                'title' => 'Capacitar al equipo',
                'description' => 'Explicar el uso de la API y la colección de Postman.',
                'status' => 'done',
                'due_date' => now()->addDays(2)->toDateString(),
            ],
        ];

        foreach ($tasks as $task) {
            Task::create([
                ...$task,
                'assignee' => fake()->randomElement($userIds),
            ]);
        }        
    }
}
