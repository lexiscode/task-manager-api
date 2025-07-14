<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;

class TaskRepository
{
    public function list()
    {
        return TaskResource::collection(
            Task::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get()
        );
    }
}
