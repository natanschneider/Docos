<?php

declare(strict_types=1);

namespace App\Models;

use Hidehalo\Nanoid\Client as Nanoid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Table extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'doc_file',
        'database_id',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model): void {
            $model->public_key = (new Nanoid())->generateId(15);
        });
    }

    /**
     * Get the database for the table.
     */
    public function database(): BelongsTo
    {
        return $this->belongsTo(Database::class);
    }

    /**
     * Get the columns for the table.
     */
    public function columns(): HasMany
    {
        return $this->hasMany(Column::class);
    }
}
