<?php
namespace App\ServiceInterfaces;

interface CategoryServiceInterface
{
    public function save($id = null, $name);
}
