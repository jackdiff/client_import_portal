<?php
namespace App\Http\Controllers;

use App\Http\Requests\AnalyzeImport;
use App\Http\Requests\ProcessImport;
use App\ServiceInterfaces\FileImportServiceInterface;
use App\ServiceInterfaces\CustomerServiceInterface;
use App\Category;

class ImportController extends Controller
{
    public function __construct(FileImportServiceInterface $fileService, CustomerServiceInterface $customerService) {
        $this->fileService = $fileService;
        $this->customerService = $customerService;
    }

    public function analyze(AnalyzeImport $request) {
        $format = $this->fileService->makeFormat($request->file('fileImport'), $this->customerService->getFields());
        if(empty($format)) {
            return response()->json([
                'success' => false,
                'errors' => ['fileImport' => __('File empty')] 
            ]);
        }
        return response()->json([
                'success' => true,
                'format' => $format
            ]);
    }

    public function process(ProcessImport $request) {
        $category_id = $request->get('category');
        $file = $request->file('fileImport');
        $fields = $request->get('fields');
        $includeFirstRow = $request->get('includeFirstRow');
        $category = null;
        if($category_id) {
            $category = Category::find($category_id);
        }
        $fields = json_decode($fields, true);

        if(empty($category) || empty($fields)) {
            return response()->json([
                'success' => false,
                'errors' => ['category' => __('Category not found')] 
            ]);
        }
        $result = $this->customerService->import($file, $category->id, $fields, $includeFirstRow);
        return response()->json([
            'success' => true,
            'message' => __("Imported :total records", [ 'total' => $result])
        ]);
    }
}
