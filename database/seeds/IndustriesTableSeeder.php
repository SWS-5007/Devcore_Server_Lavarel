<?php

use App\Models\Industry;
use Illuminate\Database\Seeder;

class IndustriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Industry::truncate();

        $data = json_decode(file_get_contents(__DIR__ . '/data/industries.json'), true);
       
        foreach($data as $name){
            $industry=new Industry();
            $industry->name=$name;
            $industry->save();
        }
    }
}
