<?php

namespace App\Models;

use App\Enums\AppPrefixEnum;
use App\Traits\HasTableName;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property Collection<Task>|null $tasks
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 */
final class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasTableName;
    use MustVerifyEmailTrait;
    use Notifiable;

    /** @inheritdoc  */
    protected $table = AppPrefixEnum::USER_MODULE->value.'__users';

    /** {@inheritdoc} */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /** {@inheritdoc} */
    protected $relations = [
        'tasks'
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'user_id');
    }

}
