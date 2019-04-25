<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use App\Http\Requests\StoreCategory;
use App\Category;
use App\ServiceInterfaces\CategoryServiceInterface;

class CategoryController extends Controller
{
    public function __construct(CategoryServiceInterface $categoryService) {
        $this->categoryService = $categoryService;
    }

    public function index() {
      $cate = Category::all();
      return response()->json(['success' => true, 'categories' => $cate->toArray()]);
    }

    public function remove($id = null) {
      if(!$id) {
        return response()->json(['success' => false, 'errors' => ['common' => __('Err! Can not delete empty item')]]);
      }
      $cate = Category::find($id);
      $cate->delete();
      return response()->json(['success' => true]);
    }

    public function add(StoreCategory $request) {
      $id = $request->get('id');
      $name = $request->get('name');
      
      try {
        $category = $this->categoryService->save($id, $name);
        return response()->json(['success' => true, 'category' => $category->toArray()]);
      } catch(QueryException $e) {
        return response()->json(['success' => false, 'errors' => ['name' => __('Can not create new one right now.')]]);
      }
    }
}
