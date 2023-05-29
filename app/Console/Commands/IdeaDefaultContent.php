<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\CompanyRole;
use App\Models\Idea;
use App\Models\IdeaContent;
use App\Models\ModelHasRole;
use App\Models\IdeaIssue;
use App\Models\Process;
use App\Models\ProcessOperation;
use App\Models\ProcessStage;
use App\Models\ProcessPhase;
use App\Notifications\IdeasSummaryNotification;
use App\Services\UserService;
use App\Services\ProcessService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;

class IdeaDefaultContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:idea:defaultcontent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets idea default content to idea description and attachment';

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
        $ideas = Idea::all();
        foreach ($ideas as $idea) {
//            $content = $idea->ideaContent()->first();
//            if (!$content) {
//                $file = $idea->resources->first();
//                $this->info(sprintf('Setting default content for idea without content: %s...', $idea->title));
//                if($file && $idea->description){
//                    $pass = array('%ideadesc%', '%fileid%', '%filehref%', '%filemime%', '%filetitle%' );
//                    $value = array($idea->description, $file->uri, $file->url, $file->mime_type, $file->title);
//                    $this->info(sprintf('Setting idea description to content: %s...', $file->title));
//                    $this->info(sprintf('Setting idea file to content: %s...', $file->title));
//
//
//                    $output_string = "{\"type\": \"doc\", \"content\": [{\"type\": \"paragraph\", \"attrs\": {\"indent\": 0}, \"content\": [{\"text\": \"%ideadesc%\", \"type\": \"text\"}]}, {\"type\": \"file\", \"attrs\": {\"id\": \"%fileid%\", \"src\": \"\", \"href\": \"%filehref%\", \"size\": null, \"type\": \"%filemime%\", \"style\": null, \"title\": \"%filetitle%\", \"preview\": false}}, {\"type\":\"paragraph\", \"attrs\": {\"indent\": 0}}]}";
//
//
//                    $ideaContent = IdeaContent::create([
//                        'content_type' => 'Custom',
//                        'markup' => json_decode(str_replace($pass, $value, $output_string)),
//                        'idea_id' => $idea->id,
//                        'version' => 1,
//                    ]);
//                    $ideaContent->save();
//                    $idea->content_id = $ideaContent->id;
//                    $idea->save();
//                } else  {
//                    $pass = array('%ideadesc%');
//                    $value = isset($idea->description) ? array($idea->description) : array('');
//                    $this->info(sprintf('Setting idea description to content: %s...', $idea->description));
//                    $output_string = (string) "{\"type\": \"doc\", \"content\": [{\"type\": \"paragraph\", \"attrs\": {\"indent\": 0}, \"content\": [{\"text\": \"%ideadesc%\", \"type\": \"text\"}]}]}";
//                    $formatted = str_replace($pass, $value, $output_string);
//                    $ideaContent = IdeaContent::create([
//                        'content_type' => 'Custom',
//                        'markup' => json_decode($formatted),
//                        'idea_id' => $idea->id,
//                        'version' => 1,
//                    ]);
//                    $ideaContent->save();
//                    $idea->content_id = $ideaContent->id;
//                    $idea->save();
//                }
//
//
//            }

        }
    }
}
