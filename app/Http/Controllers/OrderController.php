<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pizza;
use App\Models\Topping;

class OrderController extends Controller
{
    public function index()
    {
        $pizzas = Pizza::all();
        $toppings = Topping::all();
        return view('order', compact('pizzas'), compact('toppings'));
    }
}
