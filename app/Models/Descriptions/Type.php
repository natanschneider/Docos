<?php

declare(strict_types=1);

namespace App\Models\Descriptions;

use App\Models\Column;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    public $timestamps = false;

    protected $table = 'types';

    protected $fillable = [];

    public function columns(): HasMany
    {
        return $this->hasMany(Column::class);
    }
}
