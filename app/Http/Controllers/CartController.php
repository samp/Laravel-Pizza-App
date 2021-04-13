<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pizza;
use App\Models\Topping;
use App\Rules\DealMethod;
use Illuminate\Support\Facades\Auth;

use App\Classes\CartHandler;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $auth_user = json_encode($user);

        $carthandler = new CartHandler;

        // Get cart from storage
        $cart = $carthandler->get_cart();

        if ($cart == null) {
            // The cart is empty
            return view('cart')->with('auth_user', $auth_user)->with('cart', null);
        } else {
            // The cart is not empty
            $activedeals = session('deals');
            if ($activedeals != null) {
                $deals = $this->ValidateDeals($activedeals, $cart);
                $finalprice = $this->ApplyDeals($deals, $cart);
            } else {
                $finalprice = 0;
                $deals = $activedeals;
            }

            return view('cart')
                ->with('auth_user', $auth_user)
                ->with('cart', $cart)
                ->with('activedeals', $deals)
                ->with('finalprice', $finalprice);
        }
    }

    public function SubmitOrder(Request $request)
    {
        $this->validate($request, [
            'deliveryRadios' => 'required|string',
            'deals' => new DealMethod($request->deliveryRadios),
        ]);

        $carthandler = new CartHandler;
        $cartstring = $carthandler->get_cart_string();

        $request->session()->flash('finalorder', $cartstring);
        $request->session()->flash('method', $request->deliveryRadios);
        // TODO::: NOT THIS *****************************************************************************************
        $request->session()->flash('finalprice', $request->finalPrice);
        // *******************************************************************************************
        return redirect('success');
    }

    public function success(Request $request)
    {
        $order = session('finalorder');
        $method = session('method');
        $finalprice = session('finalprice');
        if ($order == null || $method == null) {
            // No order to display
            return view('success')->with('cart', null);
        } else {
            // Display order
            $carthandler = new CartHandler;
            $cart = $carthandler->session_to_object($order);
            return view('success')
                ->with('cart', $cart)
                ->with('finalprice', $finalprice)
                ->with('method', $method);
        }
    }

    public function SaveCart(Request $request)
    {
        if (Auth::check()) {
            // User is logged in
            // Get cart from storage
            $carthandler = new CartHandler;
            $cartstring = $carthandler->get_cart_string();
            if ($cartstring != null) {
                // Cart is not empty
                $user = Auth::user();
                $cartstring = serialize($cartstring);
                $user->savedorder = $cartstring;
                $user->save();
                return "Saved successfully.";
            } else {
                // Cart is empty
                return "Your cart is empty.";
            }
        } else {
            // User is not logged in
            return "You must be logged in to do this.";
        }
    }

    public function LoadCart(Request $request)
    {
        if (Auth::check()) {
            // User is logged in
            $user = Auth::user();
            $cartstring = $user->savedorder;
            if ($cartstring != null) {
                // Cart is not empty
                // Reformat data
                $carthandler = new CartHandler;
                $carthandler->clear_cart();
                $cartstring = unserialize($cartstring);
                $cart = $carthandler->session_to_object($cartstring);
                // Replace session storage
                foreach ($cart as $item) {
                    $carthandler->add_item($item);
                }
                
                return "Loaded successfully";
            } else {
                // Cart is empty
                return "No saved cart.";
            }
        } else {
            // User is not logged in
            return "You must be logged in.";
        }
    }

    public function DeleteCart(Request $request)
    {
        if (Auth::check()) {
            // User is logged in
            $user = Auth::user();
            $user->savedorder = null;
            $user->save();
            return "Data deleted.";
        } else {
            // User is not logged in
            return "You must be logged in to do this.";
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
                    //if (true) {
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
                    //if (true) {
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

    public function ApplyDeals(array $deals, array $cart)
    {
        // Pizzas being charged for
        $price = 0;

        // Two for one Tuesdays
        if (array_key_exists("Two for One Tuesdays", $deals) && $deals["Two for One Tuesdays"] == true) {
            $pizzas = [];
            foreach ($cart as $item) {
                if ($item["size"] == "Medium" || $item["size"] == "Large") {
                    $pizzas[] = $item;
                }
            }

            array_multisort(array_column($pizzas, 'price'), SORT_DESC, $pizzas);
            // Charge for highest priced pizza
            $price += $pizzas[0]["price"];
            // Remove the second highest priced pizza from cart (it is free)
            if (($key = array_search($pizzas[0], $cart)) !== false) {
                unset($cart[$key]);
            }
        }

        // Three for two Thursdays
        if (array_key_exists("Three for Two Thursdays", $deals) && $deals["Three for Two Thursdays"] == true) {
            $pizzas = [];
            foreach ($cart as $item) {
                if ($item["size"] == "Medium") {
                    $pizzas[] = $item;
                }
            }
            array_multisort(array_column($pizzas, 'price'), SORT_DESC, $pizzas);
            // Charge for 2 highest pizzas
            $price += $pizzas[0]["price"];
            $price += $pizzas[1]["price"];
            // Remove pizza 3 from cart (it is free)
            if (($key = array_search($pizzas[2], $cart)) !== false) {
                unset($cart[$key]);
            }
        }

        // Family Friday
        if (array_key_exists("Family Friday", $deals) && $deals["Family Friday"] == true) {
            $pizzas = [];
            foreach ($cart as $item) {
                if ($item["size"] == "Medium" && $item["name"] != "Create your Own") {
                    $pizzas[] = $item;
                }
            }
            array_multisort(array_column($pizzas, 'price'), SORT_DESC, $pizzas);
            // Trim so top 4 pizzas remain
            $pizzas = array_slice($pizzas, 0, 4);

            // Remove pizzas from cart - these are "free"
            foreach ($pizzas as $pizza) {
                if (($key = array_search($pizza, $cart)) !== false) {
                    unset($cart[$key]);
                }
            }
            $price += 30;
        }

        // Two Large
        if (array_key_exists("Two Large", $deals) && $deals["Two Large"] == true) {
            $pizzas = [];
            foreach ($cart as $item) {
                if ($item["size"] == "Large" && $item["name"] != "Create your Own") {
                    $pizzas[] = $item;
                }
            }
            // Trim so top 2 pizzas remain
            $pizzas = array_slice($pizzas, 0, 2);
            // Remove pizzas from cart - these are "free"
            foreach ($pizzas as $pizza) {
                if (($key = array_search($pizza, $cart)) !== false) {
                    unset($cart[$key]);
                }
            }
            $price += 30;
        }

        // Two Medium
        if (array_key_exists("Two Medium", $deals) && $deals["Two Medium"] == true) {
            $pizzas = [];
            foreach ($cart as $item) {
                if ($item["size"] == "Medium" && $item["name"] != "Create your Own") {
                    $pizzas[] = $item;
                }
            }
            // Trim so top 2 pizzas remain
            $pizzas = array_slice($pizzas, 0, 2);
            // Remove pizzas from cart - these are "free"
            foreach ($pizzas as $pizza) {
                if (($key = array_search($pizza, $cart)) !== false) {
                    unset($cart[$key]);
                }
            }
            $price += 18;
        }

        // Two Small
        if (array_key_exists("Two Small", $deals) && $deals["Two Small"] == true) {
            $pizzas = [];
            foreach ($cart as $item) {
                if ($item["size"] == "Small" && $item["name"] != "Create your Own") {
                    $pizzas[] = $item;
                }
            }
            // Trim so top 2 pizzas remain
            $pizzas = array_slice($pizzas, 0, 2);
            // Remove pizzas from cart - these are "free"
            foreach ($pizzas as $pizza) {
                if (($key = array_search($pizza, $cart)) !== false) {
                    unset($cart[$key]);
                }
            }
            $price += 12;
        }
        foreach ($cart as $item) {
            $price += $item["price"];
        }

        return $price;
    }
}
