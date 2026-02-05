<?php

declare(strict_types=1);

namespace App\Models;

use Hidehalo\Nanoid\Client as Nanoid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Endpoint extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
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
     * Get the endpoint's columns
     */
    public function columns(): BelongsToMany
    {
        return $this->belongsToMany(Column::class, 'endpoint_columns');
    }

    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($model): void {
            $model->public_key = new Nanoid()->generateId(15);
        });
    }
}
