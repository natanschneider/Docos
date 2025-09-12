<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\Endpoint;
use App\Models\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EndpointTable extends Model
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
    protected $table = 'endpoint_tables';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'endpoint_id',
        'table_id',
    ];

    /**
     * Get the endpoint's
     */
    public function endpoint(): BelongsTo
    {
        return $this->belongsTo(Endpoint::class);
    }

    /**
     * Get the table's
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }
}
