<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Column extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'doc_file',
        'table_id',
        'type_id',
    ];

    /**
     * Get the table that owns the column.
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
}
