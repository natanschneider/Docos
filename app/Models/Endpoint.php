<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}
