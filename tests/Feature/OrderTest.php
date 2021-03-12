<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    public function test_order_screen_can_be_rendered()
    {
        $response = $this->get("/order");

        $response->assertStatus(200);
    }
    public function test_named_pizza_can_be_added_to_cart()
    {
        $response = $this->post("/addtocart", [
            "pizzaRadios" => "Original",
            "sizeRadios" => "Medium",
        ]);
        $response->assertSessionHas("cart");
        //$response->assertStatus(302);
    }
    public function custom_pizza_can_be_added_to_cart()
    {
        $response = $this->get('/order');

        $response->assertStatus(200);
    }
}
