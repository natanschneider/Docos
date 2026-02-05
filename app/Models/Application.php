<?php

declare(strict_types=1);

namespace App\Models;

use Hidehalo\Nanoid\Client as Nanoid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Application extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'project_id',
    ];

    /**
     * Get the application's project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the application's endpoints
     */
    public function endpoints(): HasMany
    {
        return $this->hasMany(Endpoint::class);
    }

    /**
     * Get the application's screens
     */
    public function screens(): HasMany
    {
        return $this->hasMany(Screen::class);
    }

    /**
     * Get the application's databases
     */
    public function databases(): BelongsToMany
    {
        return $this->belongsToMany(Database::class, 'application_databases');
    }

    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($model): void {
            $model->public_key = new Nanoid()->generateId(15);
        });
    }
}
