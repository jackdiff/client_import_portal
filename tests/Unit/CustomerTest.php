<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\ServiceInterfaces\CustomerServiceInterface;
use App\Services\CustomerService;
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

    /**
     * Test import customer
     *
     * @return void
     */
    public function testSuccessImportCustomer()
    {
        //init sample data
        $sheetName = 'Sheet1';
        $wsDatas = [
            [
                'title' => $sheetName,
                'rows' => [
                    ['11', 'Comp1', 'Hanoi, VietNam', 'Mr.A', '0982837434'],
                    ['13', 'Comp2', 'Lao Cai, VietNam', 'Mr.Pham', '04345532']
                ]
            ]
        ];
        $this->instance(CustomerService::class, Mockery::mock(CustomerService::class, function ($mock) use ($wsDatas) {
            $mock->shouldReceive('getWorksheetData')
                ->once()
                ->andReturn($wsDatas);

        })->makePartial());

        $fields = [
            $sheetName => [
                'no',
                'company',
                'address',
                'name',
                'tel',
                'mobile_tel',
                'position',
                'website',
                'city'
            ]
        ];

        $service = $this->app->make(CustomerServiceInterface::class);

        $result = $service->import('text.xls', $category_id = 1, $fields, $includeFirstRow = true);

        $this->assertEquals(2, $result);
    }
}
