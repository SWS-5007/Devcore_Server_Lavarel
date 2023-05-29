<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CacheClearAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cache:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears the complete cache';

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
        Artisan::call("cache:clear", [], $this->output);
        Artisan::call("modelCache:clear", [], $this->output);
        Artisan::call("lighthouse:clear-cache", [], $this->output);
        Artisan::call("view:clear", [], $this->output);
        Artisan::call("config:clear", [], $this->output);
        Artisan::call("route:clear", [], $this->output);
        Artisan::call("optimize:clear", [], $this->output);
    }
}
