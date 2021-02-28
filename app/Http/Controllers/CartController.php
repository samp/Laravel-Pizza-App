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
        $auth_user = json_encode(Auth::user());

        // Get cart from storage
        $sessioncart = session('cart');

        if (Auth::check()) {
            // User is logged in, save cart here ??
        }

        if ($sessioncart == null) {
            // The cart is empty
            return view('cart')->with('cart', null);
        } else {
            // The cart is not empty
            $activedeals = session('deals');
            $cart = $this->SessionToCart($sessioncart);
            $deals = $this->ValidateDeals($activedeals, $cart);
            return view('cart')
                ->with('auth_user', $auth_user)
                ->with('cart', $cart)
                ->with('activedeals', $deals);
        }
    }

    public function submitorder(Request $request)
    {
        $this->validate($request, [
            'deliveryRadios' => 'required|string'
        ]);
        $sessioncart = session('cart');
        $request->session()->flush();
        $request->session()->flash('finalorder', $sessioncart);
        $request->session()->flash('method', $request->deliveryRadios);
        return redirect('success');
    }

    public function success(Request $request)
    {
        $order = session('finalorder');
        $method = session('method');
        if ($order == null || $method == null) {
            // No order to display
            return view('success')->with('cart', null);
        } else {
            // Display order
            $cart = $this->SessionToCart($order);
            return view('success')->with('cart', $cart)->with('method', $method);
        }
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

    public function ValidateDeals(array $activedeals, array $cart)
    {
        $validateddeals = [];
        foreach ($activedeals as $deal) {
            // Two for one Tuesdays
            if ($deal == "twoforonetuesdays") {
                if (date("l") == "Tuesday") {
                    $mediumcount = 0;
                    $largecount = 0;
                    foreach ($cart as $item) {
                        if ($item["size"] == "Medium") {
                            $mediumcount++;
                        }
                        if ($item["size"] == "Large") {
                            $largecount++;
                        }
                    }
                    if ($mediumcount >= 2 || $largecount >= 2) {
                        $validateddeals[$deal] = true;
                    } else {
                        $validateddeals[$deal] = false;
                    }
                } else {
                    $validateddeals[$deal] = false;
                }
            }

            // Three for two Thursdays
            if ($deal == "threefortwothursdays") {
                if (date("l") == "Thursday") {
                    $mediumcount = 0;
                    foreach ($cart as $item) {
                        if ($item["size"] == "Medium") {
                            $mediumcount++;
                        }
                    }
                    if ($mediumcount >= 3) {
                        $validateddeals[$deal] = true;
                    } else {
                        $validateddeals[$deal] = false;
                    }
                } else {
                    $validateddeals[$deal] = false;
                }
            }

            // Family Friday
            if ($deal == "familyfriday") {
                if (date("l") == "Friday") {
                    $mediumcount = 0;
                    foreach ($cart as $item) {
                        if ($item["size"] == "Medium" && $item["name"] != "Create your own") {
                            $mediumcount++;
                        }
                    }
                    if ($mediumcount >= 4) {
                        $validateddeals[$deal] = true;
                    } else {
                        $validateddeals[$deal] = false;
                    }
                } else {
                    $validateddeals[$deal] = false;
                }
            }

            // Two Large
            if ($deal == "twolarge") {
                $largecount = 0;
                foreach ($cart as $item) {
                    if ($item["size"] == "Large" && $item["name"] != "Create your own") {
                        $largecount++;
                    }
                }
                if ($largecount >= 2) {
                    $validateddeals[$deal] = true;
                } else {
                    $validateddeals[$deal] = false;
                }
            }

            // Two Medium
            if ($deal == "twomedium") {
                $mediumcount = 0;
                foreach ($cart as $item) {
                    if ($item["size"] == "Medium" && $item["name"] != "Create your own") {
                        $mediumcount++;
                    }
                }
                if ($mediumcount >= 2) {
                    $validateddeals[$deal] = true;
                } else {
                    $validateddeals[$deal] = false;
                }
            }

            // Two Small
            if ($deal == "twosmall") {
                $smallcount = 0;
                foreach ($cart as $item) {
                    if ($item["size"] == "SMall" && $item["name"] != "Create your own") {
                        $smallcount++;
                    }
                }
                if ($smallcount >= 2) {
                    $validateddeals[$deal] = true;
                } else {
                    $validateddeals[$deal] = false;
                }
            }
        }
        return $validateddeals;
    }
}
