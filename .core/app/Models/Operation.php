<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Operation extends Model
{
    protected $table = 'operations';

    // The original table has no created_at / updated_at.
    public $timestamps = false;

    protected $fillable = ['operation', 'start_date', 'end_date', 'aliases'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date:Y-m-d',
            'end_date' => 'date:Y-m-d',
        ];
    }

    public function footages(): HasMany
    {
        return $this->hasMany(Footage::class, 'operationId');
    }

    /** ['Bulge', 'Ardennes Offensive', ...] */
    public function aliasList(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->aliases))));
    }
}
