<?php

use Illuminate\Support\Facades\Schedule;

// Only runs if a cron job calls "php artisan schedule:run" every minute.
Schedule::command('database:backup')->daily();
