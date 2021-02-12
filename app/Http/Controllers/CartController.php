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

        // Get cart from storage
        $sessioncart = session('cart');

        if ($sessioncart == null) {
            // The cart is empty
            return view('cart')->with('cart', null);
        } else {
            $cart = $this->SessionToCart($sessioncart);
            return view('cart')->with('auth_user', $auth_user)
                ->with('cart', $cart);
        }
    }

    public function submitorder(Request $request)
    {
        $this->validate($request, [
            'deliveryRadios' => 'required|string'
        ]);
        $sessioncart = session('cart');
        $cart = $this->SessionToCart($sessioncart);
        $auth_user = json_encode(Auth::user());

        return redirect('success')->with('auth_user', $auth_user)
            ->with('cart', $cart);
    }

    public function success(Request $request)
    {
        return view('success');
    }

    public function SessionToCart(array $cartstring)
    {
        if ($cartstring == null) {
            // The cart is empty
            return null;
        } else {
            // The cart is not empty, convert data back to PHP objects
            foreach ($cartstring as $item) {
                $cart[] = unserialize($item);
            }
            // Get prices for each cart entry
            foreach ($cart as $key => $item) {
                $pizza = Pizza::where('name', $item["name"])->first();

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
            return $cart;
        }
    }
}
