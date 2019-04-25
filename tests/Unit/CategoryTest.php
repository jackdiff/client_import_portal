<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\ServiceInterfaces\CategoryServiceInterface;
use Illuminate\Database\Eloquent\Model;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test add category successfully in normal case
     *
     * @return void
     */
    public function testSuccessAddCategory()
    {
        $categoryName = 'New category' ;
        $service = $this->app->make(CategoryServiceInterface::class);
        $new = $service->save(null, $categoryName);

        $this->assertInstanceOf(Model::class, $new);
        $this->assertDatabaseHas('categories', [
            'name' => $categoryName
        ]);
    }

    /**
     * Test add category fail by unique constrain
     *
     * @return void
     * @expectedException Illuminate\Database\QueryException
     */
    
    public function testFailAddSameCategoryName()
    {
        $categoryA = 'categoryA' ;
        $categoryB = 'categoryA' ;
        
        $service = $this->app->make(CategoryServiceInterface::class);
        $new = $service->save(null, $categoryA);

        $this->assertInstanceOf(Model::class, $new);
        $this->assertDatabaseHas('categories', [
            'name' => $categoryA
        ]);

        $other = $service->save(null, $categoryA);
    }

    /**
     * Test get category
     *
     * @return void
     */
    public function testGetAllCategory()
    {
        $service = $this->app->make(CategoryServiceInterface::class);
        $cates = $service->all();

        $this->assertEquals($cates, []);
        $categoryA = 'categoryA' ;
        $categoryB = 'categoryB' ;
        
        $service = $this->app->make(CategoryServiceInterface::class);
        $service->save(null, $categoryA);
        $service->save(null, $categoryB);

        $this->assertDatabaseHas('categories', [
            'name' => $categoryA
        ]);
        $this->assertDatabaseHas('categories', [
            'name' => $categoryB
        ]);
    }

    /**
     * Test get delete category
     *
     * @return void
     */
    public function testSuccessDeleteCategory()
    {
        $service = $this->app->make(CategoryServiceInterface::class);

        $categoryA = 'categoryA' ;
        
        $service = $this->app->make(CategoryServiceInterface::class);
        $cateA = $service->save(null, $categoryA);

        $this->assertDatabaseHas('categories', [
            'name' => $categoryA
        ]);
        
        $service->delete($cateA->id);
        $this->assertDatabaseMissing('categories', [
            'name' => $categoryA
        ]);
    }

    /**
     * Test get delete category
     *
     * @return void
     */
    public function testEditCategory()
    {
        $categoryA = 'categoryA' ;
        
        $service = $this->app->make(CategoryServiceInterface::class);
        $cateA = $service->save(null, $categoryA);

        $this->assertDatabaseHas('categories', [
            'name' => $categoryA
        ]);
        
        $newName = 'Category C';
        $service->save($cateA->id, $newName);
        $this->assertDatabaseHas('categories', [
            'name' => $newName
        ]);
    }
}
