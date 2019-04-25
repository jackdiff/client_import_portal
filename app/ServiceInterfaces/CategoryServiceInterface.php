<?php
namespace App\ServiceInterfaces;

interface CategoryServiceInterface
{
    public function save($id = null, $name);
    public function delete($id);
    public function all();
}
