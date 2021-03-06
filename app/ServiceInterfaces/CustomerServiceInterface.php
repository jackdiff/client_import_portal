<?php
namespace App\ServiceInterfaces;

interface CustomerServiceInterface
{
    public function getFields();
    public function getFieldStyles();
    public function list($params);
    public function import($file, $category, $fields, $includeFirstRow);
    public function getWorksheetData($file);
}
