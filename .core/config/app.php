<?php

/*
 * Only the values that differ from the framework defaults live here.
 * Everything else (env, debug, url, timezone, locale, key, cipher,
 * providers, aliases, ...) comes from Laravel itself and reads .env.
 */
return [

    'name' => env('APP_NAME', 'ww2maps'),

    // Shown in the footer of the map page.
    'last_update' => env('LAST_UPDATE'),

];
