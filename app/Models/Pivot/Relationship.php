<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\Column;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relationship extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'relationships';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'primary_key_id',
        'foreign_key_id',
    ];

    /**
     * Get the primary column.
     */
    public function primaryColumn(): BelongsTo
    {
        return $this->belongsTo(Column::class, 'primary_key_id', 'id');
    }

    /**
     * Get the foreign column.
     */
    public function foreignColumn(): BelongsTo
    {
        return $this->belongsTo(Column::class, 'foreign_key_id', 'id');
    }
}
