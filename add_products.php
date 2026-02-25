<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

$products = [
    [
        'name' => 'Custom Counter Height Dining Table (Tailored Design & Perfect Fit)',
        'slug' => 'custom-counter-height-dining-table-tailored-design-perfect-fit-by-moderntouch',
        'cat'  => 'Office Furniture',
        'price'=> 25000,
        'desc' => 'Upgrade your dining or workspace with ModernTouch\'s Custom Counter Height Dining Table, designed and built to match your exact requirements. Featuring heavy metal frames and solid wooden tops, completely customizable to your needs.'
    ],
    [
        'name' => 'Fully Customizable Adjustable Folding Laptop Desk',
        'slug' => 'fully-customizable-adjustable-folding-laptop-desk-height-angle-color-personalized-bedside-computer-table',
        'cat'  => 'Office Furniture',
        'price'=> 5000,
        'desc' => 'A highly versatile, adjustable folding laptop desk. Customize the height, angle, and color to match your personal bedside or sofa workspace needs.'
    ],
    [
        'name' => 'Industrial Clothing Display Shelving',
        'slug' => 'industrial-clothing-display-shelving',
        'cat'  => 'Industrial Shelving',
        'price'=> 15000,
        'desc' => 'Heavy duty industrial style clothing display shelving unit. Built with robust metal pipes and reclaimed wood shelves, perfect for retail stores or modern closets.'
    ],
    [
        'name' => 'High Bar Table Set with 4 Chairs',
        'slug' => 'high-bar-table-set-with-4-chairs-heavy-metal-frame-with-solid-wooden-top-fully-customizable',
        'cat'  => 'Office Furniture',
        'price'=> 35000,
        'desc' => 'A complete high bar table set including 4 comfortable chairs. Features a heavy black metal frame and a solid wooden top. Fully customizable for your space.'
    ],
    [
        'name' => 'Queen Size Metal Bed Frame with Wooden Headboard & Footboard',
        'slug' => 'queen-size-metal-bed-frame-with-wooden-headboard-footboard',
        'cat'  => 'Office Furniture', // We'll just put it in Office Furniture since there's no Bedroom Furniture
        'price'=> 45000,
        'desc' => 'Sturdy and stylish queen size metal bed frame featuring a rustic wooden headboard and footboard. Engineered for durability and squeak-free sleep.'
    ],
    [
        'name' => 'Modern Office Computer Desk & Study Table',
        'slug' => 'modern-office-computer-desk-study-table-sturdy-metal-frame-minimalist-design',
        'cat'  => 'Office Furniture',
        'price'=> 12000,
        'desc' => 'Minimalist modern office computer desk and study table. Built with a sturdy metal frame and a smooth wooden working surface.'
    ],
    [
        'name' => 'Modern Industrial Style Bar Counter / Kitchen Island',
        'slug' => 'modern-industrial-style-bar-counter-kitchen-island',
        'cat'  => 'Office Furniture',
        'price'=> 40000,
        'desc' => 'A beautiful modern industrial style bar counter and kitchen island. Features a thick wooden countertop with a robust dark metal base, adding functional elegance to your kitchen.'
    ]
];

foreach ($products as $p) {
    echo "Processing: " . $p['name'] . "\n";
    $category = Category::firstOrCreate(
        ['name' => $p['cat']],
        ['slug' => Str::slug($p['cat']), 'is_active' => true]
    );

    $product = Product::where('slug', $p['slug'])->first();
    if (!$product) {
        $product = Product::create([
            'category_id' => $category->id,
            'name' => $p['name'],
            'slug' => $p['slug'],
            'sku'  => strtoupper(Str::random(8)),
            'short_description' => $p['desc'],
            'description' => '<p>' . $p['desc'] . '</p>',
            'price' => $p['price'],
            'sale_price' => null,
            'stock' => 10,
            'low_stock_threshold' => 2,
            'is_active' => true,
            'is_featured' => true,
        ]);
        echo "Created product ID: " . $product->id . "\n";
    } else {
        echo "Product already exists, skipping creation.\n";
    }

    // Try to download placeholder
    $placeholderUrl = "https://placehold.co/800x800/eeeeee/333333/png?text=" . urlencode($p['name']);
    $imageContent = @file_get_contents($placeholderUrl);
    
    if ($imageContent) {
        $filename = 'products/' . uniqid() . '.png';
        Storage::disk('public')->put($filename, $imageContent);
        
        // Save to db
        if ($product->images()->count() == 0) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $filename,
                'is_primary' => true,
                'sort_order' => 0
            ]);
            
            // Set primary image path on product table as well
            $product->update(['featured_image' => $filename]);
            echo "Added placeholder image.\n";
        }
    } else {
        echo "Failed to download placeholder image.\n";
    }
}
echo "Done.\n";
