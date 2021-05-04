<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_visit_order_page_unauthenticated()
    {
        $response = $this->get("/order");

        $response->assertStatus(200);
    }

    public function test_visit_order_page_authenticated()
    {
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user)->get("/order");

        $response->assertStatus(200);
    }

    public function test_named_pizza_price()
    {
        $this->seed();
        $cart = new \App\Classes\CartHandler;
        $cart->add_item("Original", "Small", ["cheese", "tomato sauce"]);
        $cartstring = $cart->get_cart_string();
        $response = $cart->session_to_object($cartstring)[0];
        $this->assertEquals($response->price, 8.0);
    }

    public function test_no_pizza_selected(){
        $user = \App\Models\User::factory()->create();
        $response = $this->followingRedirects()->actingAs($user)->post("/addtocart", [
            "sizeRadios" => "Medium",
        ]);
        $response->assertSessionHasErrors(["pizzaRadios"]);
    }

    public function test_no_size_selected(){
        $user = \App\Models\User::factory()->create();
        $response = $this->followingRedirects()->actingAs($user)->post("/addtocart", [
            "pizzaRadios" => "Veggie",
        ]);
        $response->assertSessionHasErrors(["sizeRadios"]);
    }

    public function test_toppings_on_named_pizza(){
        $this->seed();
        $cart = new \App\Classes\CartHandler;
        $cart->add_item("Veggie Delight", "Large", ["bacon"]);
        $cartstring = $cart->get_cart_string();
        $response = $cart->session_to_object($cartstring)[0];
        $this->assertEquals($response->toppings, ["cheese", "tomato sauce", "onions", "green peppers", "mushrooms", "sweetcorn"]);
    }

    public function test_custom_pizza_price(){
        $this->seed();
        $cart = new \App\Classes\CartHandler;
        $cart->add_item("Create your own", "Medium", ["sausage", "bacon"]);
        $cartstring = $cart->get_cart_string();
        $response = $cart->session_to_object($cartstring)[0];
        $this->assertEquals($response->price, 11);
    }

    public function test_custom_pizza_no_toppings(){
        $this->seed();
        $cart = new \App\Classes\CartHandler;
        $cart->add_item("Create your own", "Medium", []);
        $cartstring = $cart->get_cart_string();
        $response = $cart->session_to_object($cartstring)[0];
        $this->assertEquals($response->toppings, []);
        $this->assertEquals($response->price, 9);
    }

}
