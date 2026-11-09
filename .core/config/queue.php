<?php

// Framework default is the "database" queue; this site runs jobs inline.
return [

    'default' => env('QUEUE_CONNECTION', 'sync'),

];
