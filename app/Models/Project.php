<?php

declare(strict_types=1);

namespace App\Models;

use Hidehalo\Nanoid\Client as Nanoid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'company_id',
    ];

    /**
     * Get the project's company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the project's applications
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($model): void {
            $model->public_key = new Nanoid()->generateId(15);
        });
    }
}
