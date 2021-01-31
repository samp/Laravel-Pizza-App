<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToppingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('toppings')->insert([[
            'name' => 'Cheese',
        ],[
            'name' => 'Tomato sauce',
        ],[
            'name' => 'Pepperoni',
        ],[
            'name' => 'Ham',
        ],[
            'name' => 'Chicken',
        ],[
            'name' => 'Minced beef',
        ],[
            'name' => 'Onions',
        ],[
            'name' => 'Green peppers',
        ],[
            'name' => 'Mushrooms',
        ],[
            'name' => 'Sweetcorn',
        ],[
            'name' => 'Jalapeno peppers',
        ],[
            'name' => 'Pineapple',
        ],[
            'name' => 'Sausage',
        ],[
            'name' => 'Bacon',
        ]]);
    }
}
