<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Endpoint extends Model
{
    /**
     * Get the screen's application
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
