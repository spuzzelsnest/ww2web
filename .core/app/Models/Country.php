<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $table = 'countries';

    public function footages(): HasMany
    {
        return $this->hasMany(Footage::class, 'countryId');
    }
}
