<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{
    protected $table = 'sources';

    public function footages(): HasMany
    {
        return $this->hasMany(Footage::class, 'sourceId');
    }
}
