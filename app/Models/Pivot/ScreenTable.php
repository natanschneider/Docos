<?php

namespace App\Models\Pivot;

use App\Models\Table;
use App\Models\Screen;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScreenTable extends Model
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
    protected $table = 'screen_tables';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'screen_id',
        'table_id',
    ];

    /**
     * Get the related table's
    */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Get the related screen's
    */
    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }
}
