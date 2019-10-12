<?php

use Illuminate\Database\Seeder;

class partnersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('partners')->insert([
            'app_code' => 'PART1',
            'short_name' => 'LDL',
            'name' => 'Lidl',
            'desc' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam ac neque a nisi tincidunt venenatis vel nec mi. Nunc condimentum, dui a hendrerit ornare, diam lacus rutrum lorem, id blandit velit quam ut nisl. Donec placerat est erat, in auctor lectus vehicula ac.',
            'web_url' => 'www.lidl.pl',
            'logo_url' => 'xxx'
        ]);
    }
}
