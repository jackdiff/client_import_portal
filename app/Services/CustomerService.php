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

    public function import($file, $category_id, $fields, $includeFirstRow) {
        $sheetDatas = $this->getWorksheetData($file);
        $toSave = [];
        foreach($sheetDatas as $ws) {
            $count = 0;
            $structure = [];
            $sheet = $ws['title'];
            $wsFields = @$fields[$sheet];
            if(empty($wsFields)) {
                continue;
            }
            foreach ($ws['rows'] as $row) {
                $count++;
                if($count <= 1 && boolval($includeFirstRow) !== true) {
                    //1st row is header
                    continue;
                }
                $line = $this->makeRecordTemplate();
                $isEmpty = true;
                $i = 0;
                foreach ($row as $cell) {
                    if(!empty(@$wsFields[$i])) {
                        $isEmpty = false;
                        $line[@$wsFields[$i]] =  $cell;
                    }
                    $i++;
                }
                if(!$isEmpty) {
                    $line['category_id'] = $category_id;
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

    public function getWorksheetData($file) {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $worksheets = $spreadsheet->getAllSheets();

        $data = [];
        foreach($worksheets as $ws) {
            $sheet = $ws->getTitle();
            $sheetData = [
                'title' => $sheet,
                'rows' => []
            ];
            foreach ($ws->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $tmp = [];
                $isEmpty = true;
                foreach ($cellIterator as $cell) {
                    $val = trim((string)$cell->getCalculatedValue());
                    if(!empty($val)) {
                        $tmp[] = $val;
                        $isEmpty = false;
                    } else {
                        $tmp[] = null;
                    }
                }
                if(!empty($tmp) && !$isEmpty) {
                    $sheetData['rows'][] = $tmp;
                }
            }
            $data[] = $sheetData;
        }
        return $data;
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

    private function makeRecordTemplate() {
        $standardFields = $this->getFields();
        $tmp = [];
        foreach ($standardFields as $key => $value) {
            $tmp[$key] = null;
        }
        return $tmp;
    }
}
