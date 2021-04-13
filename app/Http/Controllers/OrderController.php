<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pizza;
use App\Models\Topping;
use Illuminate\Support\Facades\Auth;

use App\Classes\CartHandler;

class OrderController extends Controller
{
    public function index()
    {
        $pizzas = Pizza::all();
        $toppings = Topping::all();
        $auth_user = json_encode(Auth::user());
        return view('order')
            ->with('auth_user', $auth_user)
            ->with('pizzas', $pizzas)
            ->with('toppings', $toppings);
    }

    public function AddToCart(Request $request)
    {
        $this->validate($request, [
            'pizzaRadios' => 'required|string',
            'sizeRadios' => 'required|string'
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
        $cart = new CartHandler;
        $cart->add_item($order);
        return redirect('cart');
    }
}
