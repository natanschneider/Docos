<?php

declare(strict_types=1);

namespace App\Models;

use Hidehalo\Nanoid\Client as Nanoid;
use App\Models\Descriptions\Constraint;
use App\Models\Descriptions\Type;
use App\Models\Pivot\Index;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model): void {
            $model->public_key = (new Nanoid())->generateId(15);
        });
    }

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
    public function index(): HasOne
    {
        return $this->hasOne(Index::class, 'column_id');
    }

    /**
     * Get the column's related primary keys.
     */
    public function relatedPks(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'relationships', 'foreign_key_id', 'primary_key_id');
    }

    /**
     * Get the column's related foreign keys.
     */
    public function relatedFks(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'relationships', 'primary_key_id', 'foreign_key_id');
    }
}
