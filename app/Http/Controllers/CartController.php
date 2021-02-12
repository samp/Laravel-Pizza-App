<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pizza;
use App\Models\Topping;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $pizzas = Pizza::all();
        $toppings = Topping::all();
        $auth_user = json_encode(Auth::user());
        
        $cart = [];
        $sessioncart = session('cart');
        if ($sessioncart == null) {
            // empty cart
            return view('cart')->with('cart', null);
        } else {
            foreach ($sessioncart as $item) {
                //ddd($sessioncart);
                $cart[] = unserialize($item);
            }
            foreach ($cart as $key => $item) {
                $pizza = Pizza::where('name', $item["name"])->first();
                //$cart[$key]["toppings"] = explode(",", $item["toppings"]);

                if ($item["size"] == "Small") {
                    $cart[$key]["price"] = (float)$pizza->smallprice;
                } else if ($item["size"] == "Medium") {
                    $cart[$key]["price"] = (float)$pizza->mediumprice;
                } else if ($item["size"] == "Large") {
                    $cart[$key]["price"] = (float)$pizza->largeprice;
                }

                if ($item["name"] == "Create your own" && !empty($item["toppings"])) {
                    if ($item["size"] == "Small") {
                        $cart[$key]["price"] += count($item["toppings"]) * 0.9;
                    } else if ($item["size"] == "Medium") {
                        $cart[$key]["price"] += count($item["toppings"]);
                    } else if ($item["size"] == "Large") {
                        $cart[$key]["price"] += count($item["toppings"]) * 1.15;
                    }
                } 
            }

            //ddd($cart);

            return view('cart')->with('auth_user', $auth_user)
            ->with('cart', $cart);
        }
    }

    public function submitorder(Request $request){
        $this->validate($request, [
            'pizzaRadios' => 'required|string',
            'sizeRadios' => 'required|string',
            'toppings' => 'nullable|array',
            'method' => 'string'
        ]);

        return redirect('cart');
    }
}
