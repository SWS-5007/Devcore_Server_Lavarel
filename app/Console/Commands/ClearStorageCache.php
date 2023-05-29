<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearStorageCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:storage:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears the storage cache';

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
        $directories = Storage::disk("cache")->directories();
        foreach ($directories as $directory) {
            Storage::disk("cache")->deleteDirectory($directory);
        }

        $files = Storage::disk("cache")->files();
        foreach ($files as $file) {
            Storage::disk("cache")->delete($file);
        }
    }
}
