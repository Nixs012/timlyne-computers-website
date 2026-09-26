<?php
namespace App\Controllers\Api;

use App\Models\Category;

class CategoryController {
    public function index() {
        $categories = Category::getAll();
        
        $formatted = array_map(function($c) {
            return $c['name'];
        }, $categories);

        // Prepend "All" just like legacy JS
        array_unshift($formatted, "All");

        header('Content-Type: application/json');
        echo json_encode($formatted);
    }
}
