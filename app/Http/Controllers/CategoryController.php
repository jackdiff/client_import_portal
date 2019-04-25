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
      
      return response()->json(['success' => true, 'categories' => $this->categoryService->all()]);
    }

    public function remove($id = null) {
      if(!$id) {
        return response()->json(['success' => false, 'errors' => ['common' => __('Err! Can not delete empty item')]]);
      }
      $this->categoryService->delete($id);
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
