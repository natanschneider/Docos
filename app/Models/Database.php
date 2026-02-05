<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Descriptions\Engine;
use Hidehalo\Nanoid\Client as Nanoid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Database extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'company_id',
        'engine_id',
    ];

    /**
     * Get the database's company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the database's tables.
     */
    public function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }

    /**
     * Get the database's engine.
     */
    public function engine(): BelongsTo
    {
        return $this->belongsTo(Engine::class);
    }

    /**
     * Get the database's applications.
     */
    public function applications(): BelongsToMany
    {
        return $this->belongsToMany(Application::class, 'application_databases');
    }

    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($model): void {
            $model->public_key = new Nanoid()->generateId(15);
        });
    }
}
