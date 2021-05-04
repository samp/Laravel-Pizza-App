<?php

namespace App\Classes;

class DealHandler
{
    // Methods
    public function add_deal($dealname)
    {
        $sessiondeals = session('deals');
        if ($sessiondeals != null) {
            // Session is not empty
            if (!in_array($dealname, $sessiondeals)) {
                // Deal not already active
                session()->push("deals", $dealname);
            } else {
                // Deal is aleady active
            }
        } else {
            // Session is empty
            session()->push("deals", $dealname);
        }
    }

    public function remove_deal($dealname)
    {
        $sessiondeals = session('deals');
        if ($sessiondeals != null) {
            // Session is not empty
            // Find and remove deal
            if (($key = array_search($dealname, $sessiondeals)) !== false) {
                unset($sessiondeals[$key]);
            }
            // Update session
            session()->forget("deals");
            foreach ($sessiondeals as $deal) {
                session()->push("deals", $deal);
            }
        }
    }

    public function get_deals()
    {
        $dealstring = session('deals');
        return $dealstring;
    }

    public function clear_deals()
    {
        session()->forget('deals');
    }

    public function apply_deals(array $deals, array $cart){
        // Pizzas being charged for
        $price = 0;

        // Two for one Tuesdays
        if (array_key_exists("Two for One Tuesdays", $deals) && $deals["Two for One Tuesdays"] == true) {
            $pizzas = [];
            foreach ($cart as $item) {
                if ($item->size == "Medium" || $item->size == "Large") {
                    $pizzas[] = $item;
                }
            }

            array_multisort(array_column($pizzas, 'price'), SORT_DESC, $pizzas);

            // Charge for highest priced pizza
            $price += $pizzas[0]["price"];
            // Remove it from cart
            if (($key = array_search($pizzas[0], $cart)) !== false) {
                unset($cart[$key]);
            }

            // Remove the second highest priced pizza from cart (it is free)
            if (($key = array_search($pizzas[1], $cart)) !== false) {
                unset($cart[$key]);
            }
        }

        // Three for two Thursdays
        if (array_key_exists("Three for Two Thursdays", $deals) && $deals["Three for Two Thursdays"] == true) {
            $pizzas = [];
            foreach ($cart as $item) {
                if ($item->size == "Medium") {
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
                if ($item->size == "Medium" && $item->name != "Create your Own") {
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
                if ($item->size == "Large" && $item->name != "Create your Own") {
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
                if ($item->size == "Medium" && $item->name != "Create your Own") {
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
                if ($item->size == "Small" && $item->name != "Create your Own") {
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
            $price += $item->price;
        }
        return $price;
    }

    public function two_for_one_tuesdays(string $deal, array $cart)
    {
        // Two for one Tuesdays
        if ($deal == "twoforonetuesdays") {
            if (date("l") == "Tuesday") {
                //if (true) {
                $mediumcount = 0;
                $largecount = 0;
                foreach ($cart as $item) {
                    if ($item->size == "Medium") {
                        $mediumcount++;
                    }
                    if ($item->size == "Large") {
                        $largecount++;
                    }
                }
                if ($mediumcount >= 2 || $largecount >= 2) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function three_for_two_thursdays(string $deal, array $cart)
    {
        if ($deal == "twoforonetuesdays") {
            if (date("l") == "Thursday") {
                //if (true) {
                $mediumcount = 0;
                foreach ($cart as $item) {
                    if ($item->size == "Medium") {
                        $mediumcount++;
                    }
                }
                if ($mediumcount >= 3) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function family_friday(string $deal, array $cart)
    {
        if ($deal == "familyfriday") {
            if (date("l") == "Friday") {
                //if (true) {
                $mediumcount = 0;
                foreach ($cart as $item) {
                    if ($item->size == "Medium" && $item->name != "Create your own") {
                        $mediumcount++;
                    }
                }
                if ($mediumcount >= 4) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function two_large(string $deal, array $cart)
    {
        if ($deal == "twolarge") {
            $largecount = 0;
            foreach ($cart as $item) {
                if ($item->size == "Large" && $item->name != "Create your own") {
                    $largecount++;
                }
            }
            if ($largecount >= 2) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function two_medium(string $deal, array $cart)
    {
        if ($deal == "twomedium") {
            $mediumcount = 0;
            foreach ($cart as $item) {
                if ($item->size == "Medium" && $item->name != "Create your own") {
                    $mediumcount++;
                }
            }
            if ($mediumcount >= 2) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function two_small(string $deal, array $cart)
    {
        if ($deal == "twosmall") {
            $smallcount = 0;
            foreach ($cart as $item) {
                if ($item->size == "Small" && $item->name != "Create your own") {
                    $smallcount++;
                }
            }
            if ($smallcount >= 2) {
                return true;
            } else {
                return false;
            }
        }
    }
    public function validate_deals(array $activedeals, array $cart)
    {
        $dealhandler = new DealHandler;
        $activedeals = $dealhandler->get_deals();
        $validateddeals = [];
        foreach ($activedeals as $deal) {
            // Two for one Tuesdays
            if ($deal == "twoforonetuesdays") {
                if ($dealhandler->two_for_one_tuesdays($deal, $cart) == true) {
                    $validateddeals["Two for One Tuesdays"] = true;
                } else {
                    $validateddeals["Two for One Tuesdays"] = false;
                }
            }

            // Three for two Thursdays
            if ($deal == "threefortwothursdays") {
                if ($dealhandler->three_for_two_thursdays($deal, $cart) == true) {
                    $validateddeals["Three for Two Thursdays"] = true;
                } else {
                    $validateddeals["Three for Two Thursdays"] = false;
                }
            }

            // Family Friday
            if ($deal == "familyfriday") {
                if ($dealhandler->family_friday($deal, $cart) == true) {
                    $validateddeals["Family Friday"] = true;
                } else {
                    $validateddeals["Family Friday"] = false;
                }
            }

            // Two Large
            if ($deal == "twolarge") {
                if ($dealhandler->two_large($deal, $cart) == true) {
                    $validateddeals["Two Large"] = true;
                } else {
                    $validateddeals["Two Large"] = false;
                }
            }

            // Two Medium
            if ($deal == "twomedium") {
                if ($dealhandler->two_medium($deal, $cart) == true) {
                    $validateddeals["Two Medium"] = true;
                } else {
                    $validateddeals["Two Medium"] = false;
                }
            }

            // Two Small
            if ($deal == "twosmall") {
                if ($dealhandler->two_small($deal, $cart) == true) {
                    $validateddeals["Two Small"] = true;
                } else {
                    $validateddeals["Two Small"] = false;
                }
            }
        }
        return $validateddeals;
        
    }
    
}
