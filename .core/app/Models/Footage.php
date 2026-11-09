<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Footage extends Model
{
    protected $table = 'footages';

    protected $fillable = [
        'name',
        'info',
        'operationId',
        'date',
        'place',
        'countryId',
        'sourceId',
        'remark',
        'typeId',
        'lat',
        'lng',
        'published',
    ];

    protected function casts(): array
    {
        return [
            'lat'       => 'float',
            'lng'       => 'float',
            'published' => 'boolean',
        ];
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'typeId');
    }

    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class, 'operationId');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'countryId');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class, 'sourceId');
    }

    // Removed from the old model:
    // - setDateAttribute(): its body was commented out, so it swallowed the
    //   date and the row was saved without one.
    // - mediaType(), getOperation(), getCountry(), getSource(): invalid hasOne()
    //   calls, replaced by the belongsTo relations above.
    // - getDates(): timestamps are handled by Eloquent already.
    // - $footagesRules: replaced by App\Http\Requests\StoreFootageRequest.
}
