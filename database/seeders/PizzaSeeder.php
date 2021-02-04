<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PizzaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pizzas')->insert([
            'name' => 'Original',
            'toppings' => 'cheese,tomato sauce',
            'smallprice' => 8,
            'mediumprice' => 9,
            'largeprice' => 11,
        ]);
        DB::table('pizzas')->insert([
            'name' => 'Gimme the Meat',
            'toppings' => 'cheese,tomato sauce,pepperoni,ham,chicken,minced beef,sausage,bacon',
            'smallprice' => 11,
            'mediumprice' => 14.5,
            'largeprice' => 16.5,
        ]);
        DB::table('pizzas')->insert([
            'name' => 'Veggie Delight',
            'toppings' => 'cheese,tomato sauce,onions,green peppers,mushrooms,sweetcorn',
            'smallprice' => 10,
            'mediumprice' => 13,
            'largeprice' => 15,
        ]);
        DB::table('pizzas')->insert([
            'name' => 'Make Mine Hot',
            'toppings' => 'cheese,tomato sauce,chicken,onions,green peppers,jalapeno peppers',
            'smallprice' => 11,
            'mediumprice' => 13,
            'largeprice' => 15,
        ]);
        DB::table('pizzas')->insert([
            'name' => 'Create your own',
            'toppings' => '',
            'smallprice' => 8,
            'mediumprice' => 9,
            'largeprice' => 11,
        ]);
    }
}
