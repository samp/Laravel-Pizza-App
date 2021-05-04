<?php

namespace Tests\Feature;

use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartTest extends TestCase
{
    protected $seed = true;

    public function test_visit_cart_page_unauthenticated()
    {
        $response = $this->get("/cart");
        $response->assertStatus(200);
    }

    public function test_visit_cart_page_authenticated()
    {
        $user = \App\Models\User::factory()->create();
        $response = $this->actingAs($user)->get("/cart");
        $response->assertStatus(200);
    }
    public function test_validate_twoforonetuesdays()
    {
        $this->seed();
        $tuesday = new DateTime("2021-04-21");
        $this->travelTo($tuesday);

        //fwrite(STDERR, print_r(date("Y/m/d"), TRUE));
        $dealhandler = new \App\Classes\DealHandler;
        $carthandler = new \App\Classes\CartHandler;
        $carthandler->add_item("Original", "Medium", []);
        $carthandler->add_item("Create your own", "Medium", ["cheese", "sausage"]);
        $dealhandler->add_deal("twoforonetuesdays");
        $deals = $dealhandler->get_deals();
        $cart = $carthandler->get_cart();
        $response = $dealhandler->validate_deals($deals, $cart);
        $this->assertEquals($response, ["Two for One Tuesdays" => true]);
    }

    public function test_validate_twosmall()
    {
        $this->seed();
        //fwrite(STDERR, print_r(date("Y/m/d"), TRUE));
        $dealhandler = new \App\Classes\DealHandler;
        $carthandler = new \App\Classes\CartHandler;
        $carthandler->add_item("Original", "Large", []);
        $dealhandler->add_deal("twosmall");
        $deals = $dealhandler->get_deals();
        $cart = $carthandler->get_cart();
        $response = $dealhandler->validate_deals($deals, $cart);
        $this->assertEquals($response, ["Two Small" => false]);
    }

    public function test_twoforonetuesdays_method()
    {
        $this->seed();
        $tuesday = new DateTime("2021-04-21");
        $this->travelTo($tuesday);

        //fwrite(STDERR, print_r(date("Y/m/d"), TRUE));
        $dealhandler = new \App\Classes\DealHandler;
        $carthandler = new \App\Classes\CartHandler;
        $carthandler->add_item("Original", "Medium", []);
        $carthandler->add_item("Create your own", "Medium", ["cheese", "sausage"]);
        $dealhandler->add_deal("twoforonetuesdays");
        $deals = $dealhandler->get_deals();
        $cart = $carthandler->get_cart();
        $response = $dealhandler->validate_deals($deals, $cart);
        $this->assertEquals($response, ["Two for One Tuesdays" => true]);
    }

    public function test_save_order()
    {
        $this->seed();
        $user = \App\Models\User::factory()->create();
        $carthandler = new \App\Classes\CartHandler;
        $carthandler->add_item("Original", "Small", []);
        $carthandler->add_item("Veggie Delight", "Medium", []);
        $savedorderhandler = new \App\Classes\SavedOrderHandler;
        $response = $this->actingAs($user)->$savedorderhandler->save_cart();
        $this->assertEquals($response, "Saved successfully.");
    }

    public function test_load_order()
    {
        $this->seed();
        $user = \App\Models\User::factory()->create();
        $carthandler = new \App\Classes\CartHandler;
        $carthandler->add_item("Original", "Small", []);
        $carthandler->add_item("Veggie Delight", "Medium", []);
        $savedorderhandler = new \App\Classes\SavedOrderHandler;
        $this->actingAs($user)->$savedorderhandler->save_cart();
        session()->flush();
        $response = $this->actingAs($user)->$savedorderhandler->load_cart();
        $this->assertEquals($response, "Loaded successfully.");
    }

    public function test_delete_order()
    {
        $this->seed();
        $user = \App\Models\User::factory()->create();
        $carthandler = new \App\Classes\CartHandler;
        $carthandler->add_item("Original", "Small", []);
        $carthandler->add_item("Veggie Delight", "Medium", []);
        $savedorderhandler = new \App\Classes\SavedOrderHandler;
        $this->actingAs($user)->$savedorderhandler->save_cart();
        $response = $this->actingAs($user)->$savedorderhandler->delete_cart();
        $this->assertEquals($response, "Data deleted.");
    }
}
