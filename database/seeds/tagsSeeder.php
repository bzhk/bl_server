<?php

use Illuminate\Database\Seeder;

class tagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->insert([
            'code' => 'MET1',
            'name' => 'Metal',
        ]);
        DB::table('tags')->insert([
            'code' => 'PLA1',
            'name' => 'Plastik',
        ]);
        DB::table('tags')->insert([
            'code' => 'ELE1',
            'name' => 'Elektro-śmieci',
        ]);
        DB::table('tags')->insert([
            'code' => 'MAK1',
            'name' => 'Makulatura',
        ]);
        DB::table('tags')->insert([
            'code' => 'SZK1',
            'name' => 'Szkło',
        ]);
    
        DB::table('tags')->insert([
            'code' => 'UBR1',
            'name' => 'Ubrania',
        ]);
        DB::table('tags')->insert([
            'code' => 'GAB1',
            'name' => 'Gabaryty',
        ]);
  
    }
}
