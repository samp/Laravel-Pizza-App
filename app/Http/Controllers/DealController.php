<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DealController extends Controller
{
    public $deals = ["twoforonetuesdays", "threefortwothursdays", "familyfriday", "twolarge", "twomedium", "twosmall"];
    public function index()
    { 
        return view('deals');
    }

    public function adddeal(Request $request){
        $thisdeal = array_intersect($request->all(), $this->deals);
        $dealstring = serialize($thisdeal);
        $request->session()->push("deals", $dealstring);

        return redirect('cart');
    }
}
