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

    public function addtocart(Request $request)
    {

        $this->validate($request, [
            'pizzaRadios' => 'required|string',
            'sizeRadios' => 'required|string',
            'toppings' => 'nullable|array',
            'method' => 'string'
        ]);

        $pizzaname = $request->input('pizzaRadios');

        $order = [
            "name" => $request->pizzaRadios,
            "size" => $request->sizeRadios,
            "toppings" => [],
        ];

        // Get pizza toppings from DB
        $pizza = Pizza::where('name', $pizzaname)->first();
        if ($request->pizzaRadios != "Create your own") {
            $order["toppings"] = explode(",", $pizza->toppings);
        } else {
            $order["toppings"] = $request->toppingCheckboxes;
        }

        // Store order in session
        $orderstring = serialize($order);
        $request->session()->push("cart", $orderstring);


        return redirect('cart');
    }
}
