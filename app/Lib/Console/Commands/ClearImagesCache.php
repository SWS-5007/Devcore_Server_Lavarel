<?php

namespace App\Lib\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearImagesCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears the cache of images';

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
        Storage::disk('cache')->deleteDirectory('images');
        $this->info('Images cache cleared!');
    }
}
