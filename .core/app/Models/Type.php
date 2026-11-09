<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    protected $table = 'types';

    public function footages(): HasMany
    {
        return $this->hasMany(Footage::class, 'typeId');
    }
}
