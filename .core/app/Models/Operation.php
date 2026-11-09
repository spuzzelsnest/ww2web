<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Operation extends Model
{
    protected $table = 'operations';

    public function footages(): HasMany
    {
        return $this->hasMany(Footage::class, 'operationId');
    }
}
