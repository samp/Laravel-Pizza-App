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

        // Store order in session
        $cart = new CartHandler;
        $cart->add_item($request->pizzaRadios, $request->sizeRadios, $request->toppingCheckboxes);
        return redirect('cart');
    }
}
