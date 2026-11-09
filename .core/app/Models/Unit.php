<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = ['name', 'full_name', 'side'];

    public function stops(): HasMany
    {
        return $this->hasMany(TourStop::class);
    }
}
