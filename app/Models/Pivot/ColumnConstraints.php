<?php

namespace App\Models\Pivot;

use App\Models\Column;
use App\Models\Descriptions\Constraint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ColumnConstraints extends Model
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
    protected $table = 'column_constraints';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'column_id',
        'constraint_id',
    ];

    /**
     * Get the column's
     */
    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class);
    }

    /**
     * Get the constraint's
     */
    public function constraint(): BelongsTo
    {
        return $this->belongsTo(Constraint::class);
    }
}
