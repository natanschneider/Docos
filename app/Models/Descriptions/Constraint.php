<?php

declare(strict_types=1);

namespace App\Models\Descriptions;

use App\Models\Column;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Constraint extends Model
{
    public $timestamps = false;

    protected $table = 'constraints';

    protected $fillable = [];

    /**
     * Get the columns for the constraint.
     */
    public function columns(): BelongsToMany
    {
        return $this->belongsToMany(Column::class, 'column_constraints');
    }
}
