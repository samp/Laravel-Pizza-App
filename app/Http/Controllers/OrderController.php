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
        $auth_user = json_encode(Auth::user());
        //ddd($auth_user, $pizzas, $toppings);
        //$in = compact($auth_user, $pizzas, $toppings);
        //ddd($auth_user);
        //return view('order', $in);
        return view('order.index')
            ->with('auth_user', $auth_user)
            ->with('pizzas', $pizzas)
            ->with('toppings', $toppings);
    }

    public function confirm()
    {
        //$user->lastorder = "";
        return view('order.confirm');
    }

    public function submit(Request $request)
    {
        $this->validate($request, [
            'pizza' => 'required|string',
            'size' => 'required|string',
            'toppings' => 'nullable|array',
            'method' => 'required|string'
        ]);
        /*
          Add mail functionality here.
        */

        return response()->json(null, 200);
    }
}
