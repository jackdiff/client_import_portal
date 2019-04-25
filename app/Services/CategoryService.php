<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\ServiceInterfaces\CategoryServiceInterface;
use App\Category;

class CategoryService implements CategoryServiceInterface
{
    public function save($id = null, $name) {
        $new = null;
        if($id) {
            $new = Category::find($id);
        }
        if(!$new) {
            $new = new Category;
        }
        $new->name = $name;
        $new->save();
        return $new;
    }
}
