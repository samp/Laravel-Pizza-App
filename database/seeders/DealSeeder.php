<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('deals')->insert([
            'name' => 'Two for One Tuesdays',
            'description' => 'Two medium or large pizzas for the price of one, cheapest free. Tuesdays only.',
            'small_quantity' => 0,
            'medium_quantity' => 2,
            'large_quantity' => 2,
            'fixed_price' => 2,
            'use_price' => null,
            'days' => 'tuesday',
            'methods' => 'collection,delivery',
        ]);
        
    }
}
