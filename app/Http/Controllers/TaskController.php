<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Models\Task;
use App\Actions\TaskService;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;

final class TaskController extends Controller
{
    use HttpResponses;

    public function __construct(
        private readonly TaskService $taskService
    ) {}

    /**
    * @throws AuthorizationException|Throwable
    */
    public function index()
    {
        return $this->taskService->index();
    }

    /**
    * @throws AuthorizationException|Throwable
    */
    public function store(TaskRequest $request)
    {
        try {
            $task = $this->taskService->store($request);
            return new TaskResource($task);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 'Task creation failed', 500);
        }
    }

    /**
    * @throws AuthorizationException|Throwable
    */
    public function show(Task $task)
    {
        if ($this->isNotAuthorized($task)) {
            return $this->error('', 'You are not authorized to make this request', 403);
        }

        return new TaskResource($task); // Authorized response
    }

    /**
    * @throws AuthorizationException|Throwable
    */
    public function update(TaskRequest $request, Task $task)
    {
        try {
            if ($this->isNotAuthorized($task)) {
                return $this->error('', 'You are not authorized to make this request', 403);
            }
            $task = $this->taskService->update($request, $task);
            return new TaskResource($task);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 'Task update failed', 500);
        }
    }

    /**
    * @throws AuthorizationException|Throwable
    */
    public function destroy(TaskRequest $request, Task $task)
    {
        try {
            if ($this->isNotAuthorized($task)) {
                return $this->error('', 'You are not authorized to make this request', 403);
            }
            $this->taskService->delete($task);
            return $this->success('', 'Task has been deleted successfully!', 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 'Task deletion failed', 500);
        }
    }

    private function isNotAuthorized(Task $task): bool
    {
        return Auth::user()->id !== $task->user_id;
    }
}
