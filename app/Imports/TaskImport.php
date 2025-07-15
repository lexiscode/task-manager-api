<?php

namespace App\Imports;

use App\Models\Task;
use App\Models\User;
use App\Enums\StatusEnum;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TaskImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $data = validator($row->toArray(), [
                    'title' => ['required', 'string', 'max:255'],
                    'status' => ['nullable', Rule::in(StatusEnum::values())],
                    'assigned_to_email' => ['required','email'],
                    'due_date' => ['required','date'],
                ])->validate();

                $user = User::where('email', $data['assigned_to_email'])->first();

                if (!$user) { continue; }

                Task::create([
                    'title' => $data['title'],
                    'status' => $data['status'],
                    'user_id' => $user->id,
                    'due_date' => $data['due_date'],
                ]);
            } catch (ValidationException $e) {
                continue;
            }
        }
    }
}
