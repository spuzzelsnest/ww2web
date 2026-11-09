<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourStop extends Model
{
    protected $fillable = ['tour_id', 'footage_id', 'unit_id', 'position', 'title', 'text'];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }

    public function footage(): BelongsTo
    {
        return $this->belongsTo(Footage::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
