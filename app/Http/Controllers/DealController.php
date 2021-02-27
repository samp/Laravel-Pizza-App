<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DealController extends Controller
{
    public $deals = ["twoforonetuesdays", "threefortwothursdays", "familyfriday", "twolarge", "twomedium", "twosmall"];
    public function index()
    {
        $sessiondeals = session('deals');
        //ddd($sessiondeals);
        if ($sessiondeals == null) {
            // The deals is empty
            //ddd($sessiondeals);
            return view('deals')->with('activedeals', null);
        } else {
            //$activedeals = $this->SessionToDeals($sessiondeals);
            //ddd($activedeals);
            return view('deals')->with('activedeals', $sessiondeals);
        }
    }

    public function addremovedeal(Request $request)
    {
        // Isolate current deal
        //$thisdeal = array_intersect($request->all(), $this->deals);
        $thisdeal = $request->all();
        unset($thisdeal["_token"]);
        $action = array_values($thisdeal)[0];
        $dealname = array_keys($thisdeal)[0];

        //$dealstring = serialize($thisdeal);
        $sessiondeals = session('deals');
        if ($action == "add") {
            // Add to session
            if ($sessiondeals != null) {
                // Session is not empty
                //$activedeals = $this->SessionToDeals($sessiondeals);

                if (!in_array($dealname, $sessiondeals)) {
                    // Deal not already active
                    $request->session()->push("deals", $dealname);
                } else {
                    // Deal is aleady active
                }
            } else {
                // Session is empty
                $request->session()->push("deals", $dealname);
            }
        } elseif ($action == "remove") {
            // Remove from session
            if ($sessiondeals != null) {
                // Session is not empty
                unset($sessiondeals[$dealname]);
                $request->session()->forget("deals");
                $request->session()->push("deals", $sessiondeals);
                $sessiondeals = session('deals');
                //ddd($sessiondeals);
            }
        }


        return redirect('deals');
    }

    public function SessionToDeals(array $dealsstring)
    {

        if ($dealsstring == null) {
            // Deals is empty
            return null;
        } else {
            // Deals is not empty, convert data back to PHP objects
            $deals = [];
            foreach ($dealsstring as $item) {
                
                array_push($deals, $item);
            }
            ddd($deals);
            return $deals;
        }
    }
}
