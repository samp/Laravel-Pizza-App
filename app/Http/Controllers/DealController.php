<?php

namespace App\Http\Controllers;

use App\Classes\DealHandler;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
    {
        $dealhandler = new DealHandler;
        $sessiondeals = $dealhandler->get_deals();

        if ($sessiondeals == null) {
            return view('deals')->with('activedeals', null);
        } else {
            return view('deals')->with('activedeals', $sessiondeals);
        }
    }

    public function AddRemoveDeal(Request $request)
    {

        // Isolate current deal
        $thisdeal = $request->all();
        unset($thisdeal["_token"]);
        $action = array_values($thisdeal)[0];
        $dealname = array_keys($thisdeal)[0];
        $dealhandler = new DealHandler;
        if ($action == "add") {
            // Add to session
            $dealhandler->add_deal($dealname);
        } elseif ($action == "remove") {
            // Remove from session
            $dealhandler->remove_deal($dealname);
        }
        return redirect('deals');
    }
}
