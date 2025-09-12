<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Screen extends Model
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
     * Get the screen's tables
     */
    public function tables(): BelongsToMany
    {
        return $this->belongsToMany(Table::class, 'screen_tables');
    }
}
