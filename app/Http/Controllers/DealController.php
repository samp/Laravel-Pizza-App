<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DealController extends Controller
{
    public $deals = ["twoforonetuesdays", "threefortwothursdays", "familyfriday", "twolarge", "twomedium", "twosmall"];
    public function index()
    {
        $sessiondeals = session('deals');


        //ddd($deals);
        if ($sessiondeals == null) {
            // The cart is empty
            return view('deals')->with('deals', null);
        } else {
            $deals = $this->SessionToDeals($sessiondeals);
            return view('deals')->with('deals', $deals);
        }
    }

    public function adddeal(Request $request)
    {
        $thisdeal = array_intersect($request->all(), $this->deals);
        $dealstring = serialize($thisdeal);
        $sessiondeals = session('deals');
        if ($sessiondeals != null) {

            $deals = $this->SessionToDeals($sessiondeals);

            if (!in_array($thisdeal, $deals)) {
                // Deal not already active
                $request->session()->push("deals", $dealstring);
            } else {
                ddd($deals);
            }
        } else {
            $request->session()->push("deals", $dealstring);
        }

        return redirect('cart');
    }

    public function SessionToDeals(array $dealsstring)
    {
        if ($dealsstring == null) {
            // Deals is empty
            return null;
        } else {
            // Deals is not empty, convert data back to PHP objects
            foreach ($dealsstring as $item) {
                $deals[] = unserialize($item);
            }
            return $deals;
        }
    }
}
