<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tour extends Model
{
    protected $fillable = ['slug', 'title', 'intro', 'operationId', 'published', 'sort'];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
        ];
    }

    public function stops(): HasMany
    {
        return $this->hasMany(TourStop::class)->orderBy('position');
    }

    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class, 'operationId');
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('published', true);
    }
}
