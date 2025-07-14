<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\HasTableName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $title
 * @property string $status
 * @property Carbon|null $due_date
 * @property int $user_id
 * @property string|null $description
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
final class Task extends Model
{
    use HasFactory;
    use HasTableName;
    use SoftDeletes;

    /** @inheritdoc  */
    protected $table = 'tasks';

    /** {@inheritdoc} */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'due_date',
    ];

    /** {@inheritdoc} */
    protected $casts = [
        'due_date' => 'date',
        'status' => StatusEnum::class,
    ];

    /** {@inheritdoc} */
    protected $relations = [
        'user',
    ];

    /**
     * Get the admin who created or owns the task.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
