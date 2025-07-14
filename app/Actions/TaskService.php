<?php

declare(strict_types=1);

namespace App\Actions;

use Exception;
use App\Models\Task;
use App\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\DB;
use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\Auth;

final readonly class TaskService
{
    public function __construct(
        private TaskRepository $taskRepository
    ) {}

    public function index()
    {
        return $this->taskRepository->list();
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
            return $task;
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
