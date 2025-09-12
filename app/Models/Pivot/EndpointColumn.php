<?php

namespace App\Models\Pivot;

use App\Models\Column;
use App\Models\Endpoint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EndpointColumn extends Model
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
    protected $table = 'endpoint_columns';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'endpoint_id',
        'column_id',
    ];

    /**
     * Get the endpoint's
     */
    public function endpoint(): BelongsTo
    {
        return $this->belongsTo(Endpoint::class);
    }

    /**
     * Get the column's
     */
    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class);
    }
}
