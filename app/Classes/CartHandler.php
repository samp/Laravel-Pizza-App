<?php
namespace App\Classes;

class CartHandler
{
    // Properties
    public $items;

    // Methods
    public function add_item($item)
    {
        $this->items[] = $item;
        $orderstring = serialize($item);
        session()->push("cart", $orderstring);
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
            $this->items = $cart;
            return $cart;
        }
    }

    public function clear_cart()
    {
        $this->items = [];
        session()->forget('cart');
    }

    public function get_cart_string(){
        $cartstring = session('cart');
        return $cartstring;
    }

    public function session_to_object($cartstring){
        foreach ($cartstring as $item) {
            $cart[] = unserialize($item);
        }
        // Get prices for each cart entry
        foreach ($cart as $key => $item) {
            $pizza = \App\Models\Pizza::where('name', $item["name"])->first();

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
