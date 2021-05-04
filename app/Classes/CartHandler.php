<?php

namespace App\Classes;

use App\Classes\Item;

class CartHandler
{
    // Methods
    public function add_item(string $name, string $size, ?array $toppings)
    {
        $item = new Item;
        $item->name = $name;
        $item->size = $size;
        // Get pizza toppings from DB
        $pizza = \App\Models\Pizza::where('name', $name)->first();

        if ($name != "Create your own") {
            $item->toppings = explode(",", $pizza->toppings);
        } else {
            $item->toppings = $toppings;
        }

        $orderstring = serialize($item);
        session()->push("cart", $orderstring);
        return ($orderstring);
    }

    public function get_cart()
    {
        $cartstring = $this->get_cart_string();
        if ($cartstring == null) {
            // The cart is empty
            return null;
        } else {
            // The cart is not empty, convert data back to PHP objects
            $cart = $this->session_to_object($cartstring);
            return $cart;
        }
    }

    public function clear_cart()
    {
        session()->forget('cart');
    }

    public function get_cart_string()
    {
        $cartstring = session('cart');
        return $cartstring;
    }

    public function session_to_object($cartstring)
    {
        foreach ($cartstring as $item) {
            $cart[] = unserialize($item);
        }
        // Get prices for each cart entry
        foreach ($cart as $key => $item) {
            $pizza = \App\Models\Pizza::where('name', $item->name)->first();
            if ($item->size == "Small") {
                $cart[$key]->price = (float)$pizza->smallprice;
            } else if ($item->size == "Medium") {
                $cart[$key]->price = (float)$pizza->mediumprice;
            } else if ($item->size == "Large") {
                $cart[$key]->price = (float)$pizza->largeprice;
            }

            if ($item->name == "Create your own" && !empty($item->toppings)) {
                if ($item->size == "Small") {
                    $cart[$key]->price += count($item->toppings) * 0.9;
                } else if ($item->size == "Medium") {
                    $cart[$key]->price += count($item->toppings);
                } else if ($item->size == "Large") {
                    $cart[$key]->price += count($item->toppings) * 1.15;
                }
            }
        }
        return $cart;
    }
}
