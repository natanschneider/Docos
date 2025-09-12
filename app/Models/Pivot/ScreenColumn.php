<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\Column;
use App\Models\Screen;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScreenColumn extends Model
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
    protected $table = 'screen_columns';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'screen_id',
        'column_id',
    ];

    /**
     * Get the screen
     */
    public function screen(): BelongsTo
    {
        return $this->belongsTo(Screen::class);
    }

    /**
     * Get the column
     */
    public function column(): BelongsTo
    {
        return $this->belongsTo(Column::class);
    }
}
