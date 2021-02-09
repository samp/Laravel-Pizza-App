<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pizza;
use App\Models\Topping;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $pizzas = Pizza::all();
        $toppings = Topping::all();
        $auth_user = json_encode(Auth::user());
        //ddd($auth_user, $pizzas, $toppings);
        //$in = compact($auth_user, $pizzas, $toppings);
        //ddd($auth_user);
        //return view('order', $in);
        return view('order.index')
            ->with('auth_user', $auth_user)
            ->with('pizzas', $pizzas)
            ->with('toppings', $toppings);
    }

    public function confirm()
    {
        //$user->lastorder = "";
        return view('order.confirm');
    }

    public function addtocart(Request $request)
    {

        /*$this->validate($request, [
            'pizza' => 'required|string',
            'size' => 'required|string',
            'toppings' => 'nullable|array',
            'method' => 'string'
        ]);*/
        $pizzaname = $request->input('pizzaRadios');
        $pizza = Pizza::where('name', $pizzaname)->first();

        $order = [
            "pizza" => $request->pizzaRadios,
            "size" => $request->sizeRadios,
            "toppings" => [],
            "price" => ""
        ];

        if ($request->sizeRadios == "Small") {
            $order["price"] = $pizza->smallprice;
        } else if ($request->sizeRadios == "Medium") {
            $order["price"] = $pizza->mediumprice;
        } else if ($request->sizeRadios == "Large") {
            $order["price"] = $pizza->largeprice;
        }

        $toppingsstring = "";
        if ($request->pizzaRadios != "Create your own") {
            $order["toppings"] = $pizza->toppings;
        } else {
            $order["toppings"] = $request->toppingCheckboxes;
            $toppingsstring = implode(",", $order["toppings"]);
        }

        //ddd($request, $order);
        //session($cart);
        //session(['key' => 'value']);
        
        //$orderstring = implode(",", [$order["pizza"], $order["size"], $toppingsstring]);
        $orderstring = [[$order["pizza"], $order["size"], $toppingsstring]];
        //ddd($orderstring);
        if ($request->session()->has("cart")){
            //$old = ($request->session()->get("cart"));
            //$orderstring = $old + $orderstring;
            $request->session()->push("cart", $orderstring);
        } else{
            $request->session()->put("cart", $orderstring);
        };
        
        

        //return response()->json(null, 200);
        return redirect('cart');
    }
}
