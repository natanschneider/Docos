<?php

declare(strict_types=1);

namespace App\Models;

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
