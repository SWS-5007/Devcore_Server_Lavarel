<?php

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Currency::truncate();

        $data = json_decode(file_get_contents(__DIR__ . '/data/currencies.json'), true);

        foreach($data as $code=>$data){
            Currency::create([
                'code'=> $data['iso']['code'],
                'symbol'=>$data['units']['major']['symbol'],
                'name'=>$data['name'],
            ]);
        }
    }
}
