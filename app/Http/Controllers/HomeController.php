<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ServiceInterfaces\CustomerServiceInterface;
use App\Customer;

class HomeController extends Controller
{
    public function __construct(CustomerServiceInterface $customerService) {
        $this->customerService = $customerService;
    }

    public function index() {
        $fields = $this->customerService->getFields();
        $fieldStyles = $this->customerService->getFieldStyles();
        return view('welcome', compact('fields', 'fieldStyles'));
    }

    public function customers(Request $request) {
      $category = $request->query('category');
      $name = $request->query('name');
      $address = $request->query('address');
      $tel = $request->query('tel');

      $data = $this->customerService->list(compact('category', 'name', 'address', 'tel'));
      return response()->json([
          'success' => true,
          'customer' => $data,
      ]);
    }
}
