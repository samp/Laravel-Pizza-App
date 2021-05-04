<?php

namespace Tests\Feature;

use App\Classes\DealHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DealTest extends TestCase
{

    public function test_visit_deals_page_unauthenticated()
    {
        $response = $this->get("/deals");
        $response->assertStatus(200);
    }

    public function test_visit_deals_page_authenticated()
    {
        $user = \App\Models\User::factory()->create();
        $response = $this->actingAs($user)->get("/deals");
        $response->assertStatus(200);
    }

    public function test_add_twoforonetuesdays()
    {
        $dealhandler = new \App\Classes\DealHandler;
        $dealhandler->add_deal("twoforonetuesdays");
        $deals = $dealhandler->get_deals();
        $this->assertEquals($deals, ["twoforonetuesdays"]);
    }

    public function test_add_twosmall()
    {
        $dealhandler = new \App\Classes\DealHandler;
        $dealhandler->add_deal("twosmall");
        $deals = $dealhandler->get_deals();
        //fwrite(STDERR, print_r($deals, TRUE));
        $this->assertEquals($deals, ["twosmall"]);
    }
}
