<?php

declare(strict_types=1);

namespace App\Models\Descriptions;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public $timestamps = false;

    protected $table = 'types';

    protected $fillable = [];
}
