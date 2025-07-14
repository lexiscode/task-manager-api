<?php

declare(strict_types=1);

namespace App\Actions;

use Exception;
use App\Models\Task;
use App\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;

final readonly class TaskService
{
    public function index()
    {
        if (Auth::user()->isMember()) {
            return TaskResource::collection(Task::where('user_id', Auth::id())->get());
        }
        return TaskResource::collection(Task::all());
    }

    /**
     * @throws Exception
     */
    public function store(TaskRequest $request): Task
    {
        return DB::transaction(function () use ($request): Task {
            return Task::create([...$request->validated(), 'user_id' => Auth::user()->id,]);
        });
    }

    /**
     * @throws Exception
     */
    public function update(TaskRequest $request, Task $task): Task
    {
        return DB::transaction(function () use ($request, $task): Task {
            $task->update($request->validated());
            return $task->fresh();
        });
    }

    /**
     * @throws Exception
     */
    public function updateStatus(TaskRequest $request, Task $task): Task
    {
        return DB::transaction(function () use ($request, $task): Task {
            $task->update(['status' => $request->validated()['status']]);
            return $task->fresh();
        });
    }

    /**
     * @throws RuntimeException|Throwable
     */
    public function delete(Task $task): void
    {
        $task->delete();
    }

}
