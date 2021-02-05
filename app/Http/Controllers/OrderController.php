<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pizza;
use App\Models\Topping;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $pizzas = Pizza::all();
        $toppings = Topping::all();
        $auth_user = Auth::user();
        //ddd($auth_user, $pizzas, $toppings);
        $in = compact($auth_user, $pizzas, $toppings);
        //ddd($auth_user);
        //return view('order', $in);
        return view('order')
            ->with('auth_user', $auth_user)
            ->with('pizzas', $pizzas)
            ->with('toppings', $toppings);
    }
    public function submit(Request $request)
    {
        $this->validate($request, [
            'pizza' => 'required|string',
            'size' => 'required|string'
        ]);

        /*
          Add mail functionality here.
        */

        return response()->json(null, 200);
    }
}
