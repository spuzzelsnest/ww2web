<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFootageRequest extends FormRequest
{
    public function authorize(): bool
    {
        // The route is already behind the auth middleware; this is a second lock.
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            // Used as the file name (images/<name>.jpg) and inside page HTML:
            // letters, numbers, dot, dash, underscore and inner spaces only.
            // No slashes, quotes, angle brackets or "..".
            'name' => [
                'required', 'string', 'max:60',
                'regex:/^(?!.*\.\.)[\p{L}\p{N}_-][\p{L}\p{N}._ -]*$/u',
                'unique:footages,name',
            ],

            'typeId'      => ['required', 'integer', 'exists:types,id'],
            'operationId' => ['required', 'integer', 'exists:operations,id'],
            'countryId'   => ['required', 'integer', 'exists:countries,id'],
            'sourceId'    => ['required', 'integer', 'exists:sources,id'],

            'place'  => ['required', 'string', 'max:100'],
            'date'   => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'info'   => ['nullable', 'string', 'max:5000'],
            'remark' => ['nullable', 'string', 'max:120'],

            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],

            'published' => ['nullable', 'boolean'],

            'image' => ['required', 'file', 'image', 'mimes:jpg,jpeg', 'max:1500'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'The name may only contain letters, numbers, dots, dashes, underscores and spaces (no slashes or quotes).',
            'image.max'  => 'The picture may not be larger than 1.5 MB.',
        ];
    }
}
