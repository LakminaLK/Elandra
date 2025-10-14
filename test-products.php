<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "First 3 products:\n";
$products = Product::select('id', 'name', 'slug')->take(3)->get();

foreach ($products as $product) {
    echo "ID: {$product->id} | Slug: {$product->slug} | Name: {$product->name}\n";
}