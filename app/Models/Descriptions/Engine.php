<?php

declare(strict_types=1);

namespace App\Models\Descriptions;

use Illuminate\Database\Eloquent\Model;

class Engine extends Model
{
    public $timestamps = false;

    protected $table = 'engine';

    protected $fillable = [];
}
