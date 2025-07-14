<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;

final class TaskRepository
{
    public function list()
    {
        if (Auth::user()->isMember()) {
            return TaskResource::collection(Task::where('user_id', Auth::id())->get());
        }
        return TaskResource::collection(Task::all());
    }
}
