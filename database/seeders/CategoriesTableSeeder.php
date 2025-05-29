<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Lácteos', 'description' => 'Productos lácteos y derivados'],
            ['name' => 'Abarrotes', 'description' => 'Productos básicos de despensa'],
            ['name' => 'Frutas y Verduras', 'description' => 'Productos frescos'],
            ['name' => 'Carnes', 'description' => 'Carnes frescas y embutidos'],
            ['name' => 'Bebidas', 'description' => 'Refrescos, jugos y bebidas'],
            ['name' => 'Limpieza', 'description' => 'Productos de limpieza para el hogar'],
            ['name' => 'Higiene Personal', 'description' => 'Productos de cuidado personal'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}