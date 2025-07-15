<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Models\Task;
use App\Exports\TaskExport;
use App\Imports\TaskImport;
use App\Actions\TaskService;
use App\Traits\HttpResponses;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\AssignTaskRequest;
use App\Http\Requests\ImportTasksRequest;
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
        $this->authorize('viewAny', Task::class);
        return $this->taskService->index();
    }

    /**
    * @throws AuthorizationException|Throwable
    */
    public function store(TaskRequest $request)
    {
        $this->authorize('create', Task::class);
        try {
            $task = $this->taskService->store($request);
            return $this->success(new TaskResource($task), 'New task created successfully', 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 'Task creation failed', 500);
        }
    }

    /**
    * @throws AuthorizationException|Throwable
    */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return new TaskResource($task);
    }

    /**
    * @throws AuthorizationException|Throwable
    */
    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        try {
            if (Auth::user()->isMember()) {
                $task = $this->taskService->updateStatus($request, $task);
            } else {
                $task = $this->taskService->update($request, $task);
            }
            return $this->success(new TaskResource($task), 'New task updated successfully', 200);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 'Task update failed', 500);
        }
    }

    /**
    * @throws AuthorizationException|Throwable
    */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        try {
            $this->taskService->delete($task);
            return $this->success(null, 'Task has been deleted successfully!');
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 'Task deletion failed', 500);
        }
    }

    public function assignTask(AssignTaskRequest $request, Task $task)
    {
        $this->authorize('assign', Task::class);

        $task->update($request->validated());

        return $this->success(new TaskResource($task), 'Task assigned successfully', 200);
    }

    public function import(ImportTasksRequest $request)
    {
        $this->authorize('import', Task::class);

        try {
            Excel::import(new TaskImport, $request->file('file'));
            return $this->success(null, 'Tasks imported successfully.');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 'Task import failed', 500);
        }
    }

    public function export()
    {
        $this->authorize('export', Task::class);

        return Excel::download(new TaskExport, 'tasks.xlsx');
    }

    public function trashed()
    {
        $this->authorize('viewTrashed', Task::class);

        $tasks = Task::onlyTrashed()->latest()->get();

        return TaskResource::collection($tasks);
    }

    public function restore($id)
    {
        $task = Task::onlyTrashed()->findOrFail($id);
        $this->authorize('restore', $task);

        $task->restore();
        return $this->success(new TaskResource($task), 'Task restored successfully');
    }

    public function forceDelete($id)
    {
        $task = Task::onlyTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $task);

        $task->forceDelete();

        return $this->success(null, 'Task permanently deleted');
    }

}
