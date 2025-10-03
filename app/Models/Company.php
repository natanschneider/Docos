<?php

declare(strict_types=1);

namespace App\Models;

use Hidehalo\Nanoid\Client as Nanoid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Company extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model): void {
            $model->public_key = (new Nanoid())->generateId(15);
        });
    }

    /**
     * Get the company's users.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_company');
    }

    /**
     * Get the company's projects.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the company's databases.
     */
    public function databases(): HasMany
    {
        return $this->hasMany(Database::class);
    }
}
