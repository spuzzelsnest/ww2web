<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    protected $casts = [
        'lat'       => 'float',
        'lng'       => 'float',
        'published' => 'boolean',
    ];

    // Removed from the old model:
    // - setDateAttribute(): its body was commented out, so it swallowed the
    //   date and the row was saved without one.
    // - mediaType(), getOperation(), getCountry(), getSource(): hasOne() with
    //   plain strings is invalid, and hasOne is the wrong relation anyway
    //   (the foreign keys live in this table, so it would be belongsTo).
    // - getDates(): timestamps are handled by Eloquent already.
    // - $footagesRules: replaced by App\Http\Requests\StoreFootageRequest.
}
