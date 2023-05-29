<?php

use App\Lib\Models\Resources\Resource;
use App\Models\Company;
use App\Models\Idea;
use App\Models\ProcessStage;
use App\Services\IdeaService;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class IdeaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     #   Idea::truncate();
        File::cleanDirectory(storage_path('app/public/uploads/ideas'));
        Resource::where('owner_type', 'idea')->delete();


        $companies = Company::all()->take(4);
        $faker =  \Faker\Factory::create();
        foreach ($companies as $company) {
           # $availableTools = $company->companyTools;
            $allStages = ProcessStage::where('company_id', $company->id)->get();
            foreach ($allStages as $stage) {

                $authors = $stage->users;
                if ($authors->count() > 0) {

                    $max = random_int(50, 100);
                    for ($i = 1; $i < $max; $i++) {
                      #  $hasFile = $faker->randomElement([0, 1, 0, 0, 0, 0, 0, 0, 0]);
                        $fileType = $faker->randomElement(['jpg', 'txt']);
                        $idea = Idea::create([
                            'title' => ucwords($faker->words(2, true) . " ($i)"),
                            'company_id' => $company->id,
                            'author_id' => $authors->random()->id,
                            'process_id' => $stage->process_id,
                            'parent_id' => $stage->id,
                            'type' => 'PROCESS',
                            'parent_type' => 'process_stage',
                            'description' => $faker->paragraphs(random_int(1, 3), true),
                            'status' => $faker->randomElement(array_values(['TESTING', 'ADOPTED'])),
                        ]);
//                        if ($hasFile) {
//                            $file_path = $faker->file(__DIR__ . '/seed_files');
//                            if ($file_path) {
//                                $finfo = new finfo(FILEINFO_MIME_TYPE);
//                                $path_parts = pathinfo($file_path);
//                                $filename = $faker->words(2, true) . '.' .  $path_parts['extension'];
//                                $file = new UploadedFile(
//                                    $file_path,
//                                    $filename,
//                                    $finfo->file($file_path),
//                                    filesize($file_path),
//                                    0,
//                                    false
//                                );
////                                IdeaService::instance()->saveFile($idea, [
////                                    'file' => $file
////                                ]);
//                            }
//                        }
                    }

                    //TOOL IDEAS
//                    $max = random_int(5, 11);
//                    for ($i = 1; $i < $max; $i++) {
//                        $hasFile = $faker->randomElement([0, 1, 0, 0, 0, 0, 0, 0, 0]);
//                        $idea = Idea::create([
//                            'title' => ucwords($faker->words(2, true) . " ($i)"),
//                            'company_id' => $company->id,
//                            'author_id' => $authors->random()->id,
//                            'process_id' => $stage->process_id,
//                            'parent_id' => $stage->id,
//                            'company_tool_id' => $availableTools->random()->id,
//                            'type' => 'TOOL',
//                            'parent_type' => 'process_stage',
//                            'description' => $faker->paragraphs(random_int(1, 3), true),
//                            'status' => $faker->randomElement(array_values(['NEW', 'TESTING', 'ADOPTED'])),
//                        ]);
//                        if ($hasFile) {
//                            $file_path = $faker->file(__DIR__ . '/seed_files');
//                            if ($file_path) {
//
//                                $finfo = new finfo(FILEINFO_MIME_TYPE);
//                                $path_parts = pathinfo($file_path);
//                                $filename = $faker->words(2, true) . '.' .  $path_parts['extension'];
//                                $file = new UploadedFile(
//                                    $file_path,
//                                    $filename,
//                                    $finfo->file($file_path),
//                                    filesize($file_path),
//                                    0,
//                                    false
//                                );
//                                IdeaService::instance()->saveFile($idea, [
//                                    'file' => $file
//                                ]);
//                            }
//                        }
//                    }
                }
            }
        }
    }
}
