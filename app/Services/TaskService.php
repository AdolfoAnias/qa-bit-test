<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class TaskService
{
    public function __construct()
    {
        //
    }

    public function list(Request $request): LengthAwarePaginator
    {
        return Task::query()
            ->when(
                $request->query('status'),
                fn ($query, $status) => $query->where('status', $status)
            )
            ->when(
                $request->query('assignee_name'),
                fn ($query, $name) => $query->whereHas(
                    'assigneeUser',
                    fn ($userQuery) => $userQuery->where('name', 'like', "%{$name}%")
                )
            )
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task->fresh();
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }    
}
