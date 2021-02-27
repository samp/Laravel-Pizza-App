<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
    {
        $sessiondeals = session('deals');

        if ($sessiondeals == null) {
            return view('deals')->with('activedeals', null);
        } else {
            return view('deals')->with('activedeals', $sessiondeals);
        }
    }

    public function addremovedeal(Request $request)
    {
        // Isolate current deal
        $thisdeal = $request->all();
        unset($thisdeal["_token"]);
        $action = array_values($thisdeal)[0];
        $dealname = array_keys($thisdeal)[0];
        // Get active deals
        $sessiondeals = session('deals');

        if ($action == "add") {
            // Add to session
            if ($sessiondeals != null) {
                // Session is not empty
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
                // Find and remove deal
                if (($key = array_search($dealname, $sessiondeals)) !== false) {
                    unset($sessiondeals[$key]);
                }
                // Update session
                $request->session()->forget("deals");
                foreach ($sessiondeals as $deal){
                    $request->session()->push("deals", $deal);
                }
            }
        }
        return redirect('deals');
    }
}
