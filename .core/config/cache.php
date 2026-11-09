<?php

// Framework default is the "database" store; this site has no cache table.
// CACHE_DRIVER is the pre-Laravel 11 name, still read so old .env files work.
return [

    'default' => env('CACHE_STORE', env('CACHE_DRIVER', 'file')),

];
