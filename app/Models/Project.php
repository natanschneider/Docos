<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'company_id',
        'status',
    ];

    /**
     * Get the project's company.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
