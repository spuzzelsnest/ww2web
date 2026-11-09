<?php

namespace App\Console\Commands;

use App\Models\Footage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class LinkNowPhotos extends Command
{
    protected $signature = 'footage:link-now
                            {--dry-run : Only show what would change}';

    protected $description = 'Link images/<name>_n.jpg ("now" photos) to their footage, and unlink ones whose file is gone';

    public function handle(): int
    {
        if (! Schema::hasColumn('footages', 'now_name')) {
            $this->error('The footages table has no now_name column yet. Run "php artisan migrate" first.');

            return self::FAILURE;
        }

        $dir = public_path('images');
        $dry = (bool) $this->option('dry-run');

        // lower-cased name => name_n (as spelled on disk) for every "now"
        // picture. Matching ignores case, so StMarie03_n.jpg still finds
        // footage "stMarie03"; now_name keeps the file's own spelling, which
        // is what the (case-sensitive) web server needs.
        $onDisk = [];
        foreach (glob($dir.'/*_n.jpg') ?: [] as $file) {
            $now = basename($file, '.jpg');
            $onDisk[strtolower(substr($now, 0, -2))] = $now;
        }

        $linked = $unlinked = 0;
        $orphans = $onDisk;

        // Only photos (types 1 and 2) can have a "now" photo.
        Footage::query()->where('typeId', '<', 3)->orderBy('id')->each(function (Footage $footage) use ($onDisk, &$orphans, &$linked, &$unlinked, $dry) {
            $key = strtolower($footage->name);
            $want = $onDisk[$key] ?? null;
            unset($orphans[$key]);

            if ($footage->now_name === $want) {
                return;
            }

            $this->line(($want ? '  link   ' : '  unlink ').$footage->name.($want ? " -> {$want}.jpg" : ''));
            $want ? $linked++ : $unlinked++;

            if (! $dry) {
                $footage->forceFill(['now_name' => $want])->save();
            }
        });

        foreach ($orphans as $now) {
            $this->warn("  {$now}.jpg: no photo footage named \"".substr($now, 0, -2).'"');
        }

        $this->info(($dry ? '[dry run] ' : '')."Linked: {$linked}, unlinked: {$unlinked}, unmatched files: ".count($orphans));

        return self::SUCCESS;
    }
}
