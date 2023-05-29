<?php

namespace App\Lib\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearTempFolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temp:clear {--hours=} {--minutes=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the temp folder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $minutes = $this->option('minutes') ?? ($this->option('hours') ? $this->option('hours') * 60 : (24 * 60));
        $this->info('Deleting files older than ' . $minutes . ' minutes');
        $files = Storage::disk('temp')->files();
        $currentTime = time();
        $deleted = 0;
        foreach ($files as $file) {
            $time = (Storage::disk('temp')->lastModified($file));
            if ($currentTime - $time >= 60 *  $minutes) {
                Storage::disk('temp')->delete($file);
                $deleted++;
            }
        }
        $this->info($deleted . ' files deleted');
    }
}
