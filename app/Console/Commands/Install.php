<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Laravel\Telescope\Telescope;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the system';

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
        Telescope::stopRecording();
        Artisan::call("app:cache:clear", [], $this->output);
        Artisan::call("migrate", [], $this->output);
        Artisan::call("app:install:permissions", [], $this->output);
        if ($this->option('seed')) {
            Artisan::call("db:seed --class=CurrenciesTableSeeder", [], $this->output);
            Artisan::call("db:seed --class=UsersTableSeeder", [], $this->output);
            Artisan::call("app:update:super:permissions", [], $this->output);
        }
        Telescope::startRecording();
    }

}
