<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Database extends Model
{
    /**
     * Get the database's company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
