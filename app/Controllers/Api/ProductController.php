<?php
namespace App\Controllers\Api;

use App\Models\Product;

class ProductController {
    public function index() {
        $products = Product::getAllPublic();
        
        // Transform for JS compatibility (matches legacy/products.js structure)
        $formatted = array_map(function($p) {
            return [
                'id' => $p['id'],
                'name' => $p['name'],
                'category' => $p['category_name'],
                'icon' => $p['icon'],
                'image' => $p['image_path'],
                'price' => (float)$p['price'],
                'slug' => $p['slug'],
                'specs' => json_decode($p['specs'], true) ?? []
            ];
        }, $products);

        header('Content-Type: application/json');
        echo json_encode($formatted);
    }
}
