<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pizza;
use App\Models\Topping;
use App\Rules\DealMethod;
use Illuminate\Support\Facades\Auth;

use App\Classes\CartHandler;
use App\Classes\DealHandler;
use App\Classes\SavedOrderHandler;

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
            $dealhandler = new DealHandler;
            $activedeals = $dealhandler->get_deals();
            if ($activedeals != null) {
                $deals = $dealhandler->validate_deals($activedeals, $cart);
                $totalprice = $dealhandler->apply_deals($deals, $cart);
            } else {
                $totalprice = 0;
                foreach ($cart as $item) {
                    $totalprice += $item->price;
                }
                $deals = $activedeals;
            }

            return view('cart')
                ->with('auth_user', $auth_user)
                ->with('cart', $cart)
                ->with('activedeals', $deals)
                ->with('finalprice', $totalprice);
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

        $cart = $carthandler->get_cart();
        $dealhandler = new DealHandler;
        $activedeals = $dealhandler->get_deals();
        if ($activedeals != null) {
            $deals = $dealhandler->validate_deals($activedeals, $cart);

            $totalprice = $dealhandler->apply_deals($deals, $cart);
        } else {
            $totalprice = 0;
            foreach ($cart as $item) {
                $totalprice += $item["price"];
            }
        }
        $request->session()->flash('finalprice', $totalprice);
        return redirect('success');
    }

    public function success(Request $request)
    {
        $order = session('finalorder');
        $method = session('method');
        $finalprice = session('finalprice');
        $carthandler = new CartHandler;
        $carthandler->clear_cart();
        $dealhandler = new DealHandler;
        $dealhandler->clear_deals();
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

    public function SaveCart()
    {
       $savedorderhandler = new SavedOrderHandler;
       return $savedorderhandler->save_cart();
    }

    public function LoadCart()
    {
        $savedorderhandler = new SavedOrderHandler;
       return $savedorderhandler->load_cart();
    }

    public function DeleteCart()
    {
        $savedorderhandler = new SavedOrderHandler;
       return $savedorderhandler->delete_cart();
    }
}
