<?php

// Framework default is the "database" driver; this site has no sessions table.
return [

    'driver' => env('SESSION_DRIVER', 'file'),

];
