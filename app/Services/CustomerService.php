<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\ServiceInterfaces\CustomerServiceInterface;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Customer;

class CustomerService implements CustomerServiceInterface
{
    public $PAGE_SIZE = 25;

    public function getFields() {
        return [
            'no' => 'No.',
            'company' => 'Công ty',
            'address' => 'Địa chỉ',
            'name' => 'Tên',
            'tel' => 'SĐT',
            'mobile_tel' => 'Di động',
            'position' => 'Chức vụ',
            'website' => 'Địa chỉ Web',
            'city' => 'Tỉnh thành'
        ];
    }

    public function getFieldStyles() {
        return [
            'no' => 'm50',
            'company' => 'm150',
            'address' => 'm250',
            'name' => 'm150',
            'tel' => 'm150',
            'mobile_tel' => 'm150',
            'position' => 'm150',
            'website' => 'm150',
            'city' => 'm150',
        ];
    }

    public function import($file, $category, $fields, $includeFirstRow) {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $worksheets = $spreadsheet->getAllSheets();
        $toSave = [];
        foreach($worksheets as $ws) {
            $count = 0;
            $structure = [];
            $sheet = $ws->getTitle();
            $wsFields = @$fields[$sheet];
            if(empty($wsFields)) {
                continue;
            }
            foreach ($ws->getRowIterator() as $row) {
                $count++;
                if($count <= 1 && boolval($includeFirstRow) !== true) {
                    //1st row is header
                    continue;
                }
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $line = [];
                $i = 0;
                foreach ($cellIterator as $cell) {
                    $val = trim((string)$cell->getCalculatedValue());
                    if(!empty(@$wsFields[$i])) {
                        $line[@$wsFields[$i]] =  !empty($val) ? $val : null;
                    }
                    $i++;
                }
                if(!empty($line)) {
                    $line['category_id'] = $category->id;
                    $line['sheet_source'] = $sheet;
                    array_push($toSave, $line);
                }
            }
        }
        $record_count = 0;
        if(!empty($toSave)) {
            Customer::insert($toSave);
            $record_count = count($toSave);
        }
        return $record_count;
    }

    public function list($params) {
        $query = DB::table('customers');
        if(!empty(@$params['category'])) {
            $query->where('category_id', $params['category']);
        }
        if(!empty(@$params['name'])) {
            $query->where('name', 'like', "%{$params['name']}%");
        }
        if(!empty(@$params['address'])) {
            $query->where('address', 'like', "%{$params['address']}%"); 
        }
        if(!empty(@$params['tel'])) {
            $query->where('tel', 'like', "%{$params['tel']}%");  
        }

        return $query->paginate($this->PAGE_SIZE, ['*'], 'page', $params['page'])->toArray();
    }
}
