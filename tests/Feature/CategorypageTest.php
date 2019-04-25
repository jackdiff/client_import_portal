<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Category;

class CategorypageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Access to category page success
     *
     * @return void
     */
    public function testAccessCategoryPage()
    {
        $response = $this->get('/category');

        $response->assertStatus(200);
    }

    /**
     * Test get all category
     *
     * @return void
     */
    public function testGetAllCategoryOk()
    {
        //init data
        $category_count = 10;
        $categories = factory(Category::class, $category_count)->make();
        foreach ($categories as $test) {
            $test->save();
        }
        $response = $this->get('/categories');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $response->assertJsonCount($category_count, 'categories');
    }

    /**
     * Test create new category ok
     *
     * @return void
     */
    public function testCreateNewCategoryOk()
    {
        $new = 'Potential';
        $response = $this->json('POST', '/category/add', ['name' => $new]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $response->assertJson([
            'category' => [
                'name' => $new
            ]
        ]);

    }

    /**
     * Test create duplicated category ok
     *
     * @return void
     */
    public function testCreateDuplicatedCategoryOk()
    {
        $new = 'Potential';
        $response = $this->json('POST', '/category/add', ['name' => $new]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $response->assertJson([
            'category' => [
                'name' => $new
            ]
        ]);

        $response = $this->json('POST', '/category/add', ['name' => $new]);
        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
        ]);
    }
}
