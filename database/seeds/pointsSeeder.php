<?php

use Illuminate\Database\Seeder;

class pointsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('points')->insert([
            'code' => 'MARK1',
            'name' => 'Market Lidl',
            'desc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'phone' => rand(100000000, 999999999),
            'email' => Str::random(10).'@gmail.com',
            'address' => 'Wołoska 16, 62-080 Warszawa',
            'open' => json_encode('{"open":[
                {"Monday": "00:00-00:00"},
                {"Tuesday": "00:00-00:00"},
                {"Wednesday": "00:00-00:00"},
                {"Thursday": "00:00-00:00"},
                {"Friday": "00:00-00:00"},
                {"Saturday": "00:00-00:00"},
                {"Sunday": "00:00-00:00"},
            ]}'),
            'lang' => 21.002817,
            'lat' => 52.1827006,
            
        ]);
    }
}
