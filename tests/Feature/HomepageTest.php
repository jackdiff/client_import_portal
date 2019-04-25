<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test access to home page ok
     *
     * @return void
     */
    public function testHomePageAccessOk()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test ajax get customer
     *
     * @return void
     */
    public function testAjaxGetCustomer()
    {
        $response = $this->get('/customers');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }
}
