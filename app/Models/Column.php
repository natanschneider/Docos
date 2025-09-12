<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Screen;
use App\Models\Endpoint;
use App\Models\Pivot\Index;
use App\Models\Descriptions\Type;
use App\Models\Descriptions\Constraint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /**
     * Get the column's type.
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Get the column's constraints.
     */
    public function constraints(): BelongsToMany
    {
        return $this->belongsToMany(Constraint::class, 'column_constraints');
    }

    /**
     * Get the column's endpoints.
     */
    public function endpoints(): BelongsToMany
    {
        return $this->belongsToMany(Endpoint::class, 'endpoint_columns');
    }

    /**
     * Get the column's screens.
     */
    public function screens(): BelongsToMany
    {
        return $this->belongsToMany(Screen::class, 'screen_columns');
    }

    /**
     * Get the column's indexes.
     */
    public function indexes(): BelongsToMany
    {
        return $this->belongsToMany(Index::class, 'indexes');
    }

    /**
     * Get the column's related columns.
     */
    public function relatedColumns(): BelongsToMany
    {
        return $this->belongsToMany(Column::class, 'relationships', 'primary_key_id', 'foreing_key_id');
    }
}
