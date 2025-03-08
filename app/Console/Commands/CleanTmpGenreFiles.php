<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanTmpGenreFiles extends Command
{
    protected $signature = 'cleanup:tmp-genre';
    protected $description = 'Delete temporary files in tmp/genre older than 5 minutes';

    public function handle()
    {
        $disk = Storage::disk('public');
        $files = $disk->files('tmp/genre');
        $now = Carbon::now();

        foreach ($files as $file) {
            // Get the last modified time of the file
            $lastModifiedTimestamp = $disk->lastModified($file);
            $lastModified = Carbon::createFromTimestamp($lastModifiedTimestamp);

            if ($now->diffInMinutes($lastModified) > 5) {
                $disk->delete($file);
                $this->info("Deleted: {$file}");
            }
        }

        $this->info('Temporary files cleanup complete.');
    }
}

// php artisan schedule:work
// * * * * * php /path/to/your/project/artisan schedule:run >> /dev/null 2>&1

