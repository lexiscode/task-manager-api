<?php

namespace App\Exports;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TaskExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $query = Task::with('user')->select(['title', 'description', 'status', 'user_id', 'due_date']);

        if (Auth::user()->isMember()) {
            $query->where('user_id', Auth::id());
        }

        return $query->get()->map(function ($task) {
            return [
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status->value ?? $task->status,
                'assigned_to_email' => $task->user?->email,
                'due_date' => $task->due_date?->toDateString(),
            ];
        });
    }

    public function headings(): array
    {
        return ['Title', 'Description', 'Status', 'Assigned To (Email)', 'Due Date'];
    }
}
