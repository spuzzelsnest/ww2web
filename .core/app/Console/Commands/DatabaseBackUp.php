<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DatabaseBackUp extends Command
{
    protected $signature = 'database:backup';

    protected $description = 'Dump the MySQL database to storage/app/backup/backup-YYYY-MM-DD.gz';

    public function handle(): int
    {
        // config() instead of env(): env() returns null once the config is cached.
        $db = config('database.connections.mysql');

        $dir = storage_path('app/backup');
        File::ensureDirectoryExists($dir);

        $file = $dir.'/backup-'.now()->format('Y-m-d').'.gz';

        // Password via the environment so it does not show up in the process list.
        $command = sprintf(
            'MYSQL_PWD=%s mysqldump --no-tablespaces --user=%s --host=%s --port=%s %s | gzip > %s',
            escapeshellarg((string) $db['password']),
            escapeshellarg((string) $db['username']),
            escapeshellarg((string) $db['host']),
            escapeshellarg((string) $db['port']),
            escapeshellarg((string) $db['database']),
            escapeshellarg($file)
        );

        exec('set -o pipefail; '.$command, $output, $status);

        if ($status !== 0) {
            $this->error('Backup failed (mysqldump exit code '.$status.').');

            return self::FAILURE;
        }

        $this->info('Backup written to '.$file);

        return self::SUCCESS;
    }
}
