<?php

namespace App\Console\Commands;

use App\Support\Thumbnails;
use Illuminate\Console\Command;
use Throwable;

class MakeThumbnails extends Command
{
    protected $signature = 'images:thumbs
                            {--force : Rebuild thumbnails that already exist}';

    protected $description = 'Make the small 480/960 px copies of images/*.jpg used on the map page';

    public function handle(Thumbnails $thumbs): int
    {
        // Decoding a large JPEG takes width x height x 5 bytes.
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $files = glob($thumbs->sourceDir().'/*.jpg') ?: [];
        $force = (bool) $this->option('force');

        $made = $skipped = $failed = 0;
        $bar = $this->output->createProgressBar(count($files));

        foreach ($files as $file) {
            $name = basename($file, '.jpg');

            if (! $force && $thumbs->exists($name)) {
                $skipped++;
            } else {
                try {
                    $thumbs->make($name, $force);
                    $made++;
                } catch (Throwable $e) {
                    $failed++;
                    $this->newLine();
                    $this->warn($name.': '.$e->getMessage());
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Made: {$made}, already there: {$skipped}, failed: {$failed} ({$thumbs->extension()})");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
