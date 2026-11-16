<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService,
    ) {}

    #[OA\Get(
        path: "/tasks",
        summary: "Listar todas las tareas",
        tags: ["Tareas"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Lista de tareas obtenida con éxito"
            )
        ]
    )]
    public function index(): AnonymousResourceCollection
    {
        $tasks = $this->taskService->list(request());

        return TaskResource::collection($tasks);
    }

    #[OA\Post(
        path: "/tasks",
        summary: "Crear una nueva tarea",
        tags: ["Tareas"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["title"],
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Completar documentación API"),
                    new OA\Property(property: "description", type: "string", nullable: true, example: "Añadir atributos de Swagger a los controladores"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Tarea creada exitosamente"),
            new OA\Response(response: 422, description: "Error de validación en los datos enviados")
        ]
    )]
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->create($request->validated());

        return TaskResource::make($task)
            ->response()
            ->setStatusCode(201);
    }

    #[OA\Get(
        path: "/tasks/{task}",
        summary: "Mostrar el detalle de una tarea",
        tags: ["Tareas"],
        parameters: [
            new OA\Parameter(
                name: "task",
                in: "path",
                required: true,
                description: "ID de la tarea",
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Detalle de la tarea obtenido"),
            new OA\Response(response: 404, description: "Tarea no encontrada")
        ]
    )]
    public function show(Task $task): TaskResource
    {
        return TaskResource::make($task);
    }

    #[OA\Put(
        path: "/tasks/{task}",
        summary: "Actualizar una tarea existente",
        tags: ["Tareas"],
        parameters: [
            new OA\Parameter(
                name: "task",
                in: "path",
                required: true,
                description: "ID de la tarea a actualizar",
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Tarea actualizada"),
                    new OA\Property(property: "description", type: "string", nullable: true, example: "Nueva descripción"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Tarea actualizada correctamente"),
            new OA\Response(response: 404, description: "Tarea no encontrada"),
            new OA\Response(response: 422, description: "Error de validación")
        ]
    )]    
    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        Gate::authorize('update', $task);

        $task = $this->taskService->update($task, $request->validated());

        return TaskResource::make($task);
    }

    #[OA\Delete(
        path: "/tasks/{task}",
        summary: "Eliminar una tarea",
        tags: ["Tareas"],
        parameters: [
            new OA\Parameter(
                name: "task",
                in: "path",
                required: true,
                description: "ID de la tarea a eliminar",
                schema: new OA\Schema(type: "integer", example: 1)
            )
        ],
        responses: [
            new OA\Response(response: 200, description: "Tarea eliminada correctamente"),
            new OA\Response(response: 404, description: "Tarea no encontrada")
        ]
    )]
    public function destroy(Task $task): JsonResponse
    {
        Gate::authorize('delete', $task);
        
        $this->taskService->delete($task);

        return response()->json([
            'message' => 'Tarea eliminada correctamente'
        ], 200);
    }
}
