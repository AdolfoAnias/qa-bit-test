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

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService,
    ) {}

    public function index(): AnonymousResourceCollection
    {
        $tasks = $this->taskService->list(request());

        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->create($request->validated());

        return TaskResource::make($task)
            ->response()
            ->setStatusCode(201);
    }

    public function show(Task $task): TaskResource
    {
        return TaskResource::make($task);
    }

    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $task = $this->taskService->update($task, $request->validated());

        return TaskResource::make($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->taskService->delete($task);

        return response()->json([
            'message' => 'Tarea eliminada correctamente'
        ], 200);
        
    }
}
