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
        $user = Auth::user();
        $auth_user = json_encode($user);

        if (Auth::check()) {
            // User is logged in
            if ($user->savedorder) {
                // User has an order saved
                $savedorder = $user->savedorder;
            } else {
                // User does not have an order saved
                $savedorder = null;
            };
        } else {
            $savedorder = null;
        }

        // Get cart from storage
        $sessioncart = session('cart');

        if ($sessioncart == null) {
            // The cart is empty
            return view('cart')->with('auth_user', $auth_user)->with('cart', null);
        } else {
            // The cart is not empty
            $activedeals = session('deals');
            $cart = $this->SessionToCart($sessioncart);
            if ($activedeals != null) {
                $deals = $this->ValidateDeals($activedeals, $cart);
            } else {
                $deals = $activedeals;
            }

            return view('cart')
                ->with('auth_user', $auth_user)
                ->with('cart', $cart)
                ->with('activedeals', $deals);
        }
    }

    public function SubmitOrder(Request $request)
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

    public function SaveCart(Request $request)
    {

        if (Auth::check()) {
            // User is logged in
            // Get cart from storage
            $sessioncart = session('cart');
            if ($sessioncart != null) {
                // Cart is not empty
                $user = Auth::user();
                $user->savedorder = $sessioncart;
                $user->save();
                return view('cart');
            } else {
                // Cart is empty
                return view('cart')->withErrors('Your cart is empty.');
            }
        } else {
            ddd($request);
            // User is not logged in
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
                $dealname = "Two for One Tuesdays";
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
                        $validateddeals[$dealname] = true;
                    } else {
                        $validateddeals[$dealname] = false;
                    }
                } else {
                    $validateddeals[$dealname] = false;
                }
            }

            // Three for two Thursdays
            if ($deal == "threefortwothursdays") {
                $dealname = "Three for Two Thursdays";
                if (date("l") == "Thursday") {
                    $mediumcount = 0;
                    foreach ($cart as $item) {
                        if ($item["size"] == "Medium") {
                            $mediumcount++;
                        }
                    }
                    if ($mediumcount >= 3) {
                        $validateddeals[$dealname] = true;
                    } else {
                        $validateddeals[$dealname] = false;
                    }
                } else {
                    $validateddeals[$dealname] = false;
                }
            }

            // Family Friday
            if ($deal == "familyfriday") {
                $dealname = "Family Friday";
                if (date("l") == "Friday") {
                    $mediumcount = 0;
                    foreach ($cart as $item) {
                        if ($item["size"] == "Medium" && $item["name"] != "Create your own") {
                            $mediumcount++;
                        }
                    }
                    if ($mediumcount >= 4) {
                        $validateddeals[$dealname] = true;
                    } else {
                        $validateddeals[$dealname] = false;
                    }
                } else {
                    $validateddeals[$dealname] = false;
                }
            }

            // Two Large
            if ($deal == "twolarge") {
                $dealname = "Two Large";
                $largecount = 0;
                foreach ($cart as $item) {
                    if ($item["size"] == "Large" && $item["name"] != "Create your own") {
                        $largecount++;
                    }
                }
                if ($largecount >= 2) {
                    $validateddeals[$dealname] = true;
                } else {
                    $validateddeals[$dealname] = false;
                }
            }

            // Two Medium
            if ($deal == "twomedium") {
                $dealname = "Two Medium";
                $mediumcount = 0;
                foreach ($cart as $item) {
                    if ($item["size"] == "Medium" && $item["name"] != "Create your own") {
                        $mediumcount++;
                    }
                }
                if ($mediumcount >= 2) {
                    $validateddeals[$dealname] = true;
                } else {
                    $validateddeals[$dealname] = false;
                }
            }

            // Two Small
            if ($deal == "twosmall") {
                $dealname = "Two Small";
                $smallcount = 0;
                foreach ($cart as $item) {
                    if ($item["size"] == "SMall" && $item["name"] != "Create your own") {
                        $smallcount++;
                    }
                }
                if ($smallcount >= 2) {
                    $validateddeals[$dealname] = true;
                } else {
                    $validateddeals[$dealname] = false;
                }
            }
        }
        return $validateddeals;
    }
}
