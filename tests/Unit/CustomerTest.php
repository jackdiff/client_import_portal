<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\ServiceInterfaces\CustomerServiceInterface;
use Illuminate\Database\Eloquent\Model;
use App\Customer;

class CustomerTest extends TestCase
{
     use RefreshDatabase;
    
    /**
     * Test get paginate customer
     *
     * @return void
     */
    public function testSuccessPaginateCustomer()
    {
        //init sample data
        $customers = factory(Customer::class, 50)->make();
        foreach ($customers as $test) {
            $test->save();
        }

        $service = $this->app->make(CustomerServiceInterface::class);
        $page = 1;

        $result = $service->list(compact('page'));

        $this->assertArrayHasKey('data',$result);
        $this->assertEquals($service->PAGE_SIZE, count($result['data']));
        $this->assertEquals(1, $result['current_page']);
        $this->assertEquals(2, $result['last_page']);

        $page = 3;
        $result = $service->list(compact('page'));
        $this->assertArrayHasKey('data',$result);
        $this->assertEquals(0, count($result['data']));
        $this->assertEquals(3, $result['current_page']);
        $this->assertEquals(2, $result['last_page']);
    }
}
