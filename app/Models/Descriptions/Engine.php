<?php

declare(strict_types=1);

namespace App\Models\Descriptions;

use App\Models\Database;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Engine extends Model
{
    public $timestamps = false;

    protected $table = 'engines';

    protected $fillable = [];

    /**
     * Get the databases related to the engine.
     */
    public function database(): HasMany
    {
        return $this->hasMany(Database::class);
    }
}
