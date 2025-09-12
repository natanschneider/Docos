<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Table;
use App\Models\Column;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Endpoint extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'status',
        'doc_file',
        'application_id',
    ];

    /**
     * Get the screen's application
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the endpoint's columns
     */
    public function columns(): BelongsToMany
    {
        return $this->belongsToMany(Column::class, 'endpoint_columns');
    }

    /**
     * Get the endpoint's tables
     */
    public function tables(): BelongsToMany
    {
        return $this->belongsToMany(Table::class, 'endpoint_tables');
    }
}
