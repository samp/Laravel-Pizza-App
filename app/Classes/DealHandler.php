<?php

namespace App\Classes;

class DealHandler
{
    // Properties
    public $deals;

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

    public function two_for_one_tuesdays(array $deal, array $cart)
    {
        // Two for one Tuesdays
        if ($deal == "twoforonetuesdays") {
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
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function three_for_two_thursdays(array $deal, array $cart)
    {
        if ($deal == "twoforonetuesdays") {
            if (date("l") == "Thursday") {
                //if (true) {
                $mediumcount = 0;
                foreach ($cart as $item) {
                    if ($item["size"] == "Medium") {
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

    public function family_friday(array $deal, array $cart)
    {
        if ($deal == "familyfriday") {
            if (date("l") == "Friday") {
                //if (true) {
                $mediumcount = 0;
                foreach ($cart as $item) {
                    if ($item["size"] == "Medium" && $item["name"] != "Create your own") {
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

    public function two_large(array $deal, array $cart)
    {
        if ($deal == "twolarge") {
            $largecount = 0;
            foreach ($cart as $item) {
                if ($item["size"] == "Large" && $item["name"] != "Create your own") {
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

    public function two_medium(array $deal, array $cart)
    {
        if ($deal == "twomedium") {
            $mediumcount = 0;
            foreach ($cart as $item) {
                if ($item["size"] == "Medium" && $item["name"] != "Create your own") {
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

    public function two_small(array $deal, array $cart)
    {
        if ($deal == "twosmall") {
            $smallcount = 0;
            foreach ($cart as $item) {
                if ($item["size"] == "SMall" && $item["name"] != "Create your own") {
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
}
