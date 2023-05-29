<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Carbon::serializeUsing(function ($carbon) {
            return $carbon->format('c');
        });
        $this->registerWebSockets();
    }

    protected function registerWebSockets()
    {
        $this->app->singleton('websockets.router', function () {
            return new \App\WebSockets\Server\Router();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'user' => \App\Models\User::class,
            'idea' => \App\Models\Idea::class,
            'idea_content' => \App\Models\IdeaContent::class,
            'idea_issue' => \App\Models\IdeaIssue::class,
            'process' => \App\Models\Process::class,
            'process_stage' => \App\Models\ProcessStage::class,
            'process_operation' => \App\Models\ProcessOperation::class,
            'process_phase' => \App\Models\ProcessPhase::class,
            'project' => \App\Models\Project::class,
            'project_idea' => \App\Models\ProjectIdea::class,
            'tool' => \App\Models\Tool::class,
        ]);

        $queryLogsEnabled = env("QUERY_LOG", false);
        if($queryLogsEnabled){
            DB::listen(function($query) {
                File::append(
                    storage_path('/logs/query.log'),
                    '[' . date('Y-m-d H:i:s') . ']' . PHP_EOL . $query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL . PHP_EOL
                );
            });
        }

    }
}
