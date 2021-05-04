<?php

namespace App\Classes;

use App\Classes\Item;
use Illuminate\Support\Facades\Auth;

class SavedOrderHandler
{
    // Methods
    public function save_cart()
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

    public function load_cart()
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
                    $carthandler->add_item($item->name, $item->size, $item->toppings);
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

    public function delete_cart()
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
}