<?php

declare(strict_types=1);

namespace App\Models\Pivot;

use App\Models\Application;
use App\Models\Database;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationDatabases extends Model
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
    protected $table = 'application_databases';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'application_id',
        'database_id',
    ];

    /**
     * Get the application's
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the database's
     */
    public function database(): BelongsTo
    {
        return $this->belongsTo(Database::class);
    }
}
