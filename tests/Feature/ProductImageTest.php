<?php

use App\Livewire\Admin\ProductManagement;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('product can be created without an image', function () {
    Storage::fake('public');

    $product = Product::create([
        'name' => 'No Image Product',
        'base_price' => 29.99,
        'unit' => 'Piece',
        'is_active' => true,
        'image_path' => null,
    ]);

    ProductVariant::create([
        'product_id' => $product->id,
        'variant_name' => 'Default',
        'current_stock' => 10,
        'low_stock_threshold' => 5,
    ]);

    $this->assertDatabaseHas('products', ['name' => 'No Image Product', 'image_path' => null]);
});

test('product can be created with a valid image', function () {
    Storage::fake('public');

    $image = UploadedFile::fake()->image('product.jpg', 200, 200)->size(100);

    $product = Product::create([
        'name' => 'Image Product',
        'base_price' => 49.99,
        'unit' => 'Piece',
        'is_active' => true,
        'image_path' => $image->store('products', 'public'),
    ]);

    $this->assertDatabaseHas('products', ['name' => 'Image Product']);
    $this->assertNotNull($product->image_path);
    $this->assertStringStartsWith('products/', $product->image_path);
});

test('invalid file type is rejected by livewire upload validation', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(ProductManagement::class)
        ->set('name', 'Test Product')
        ->set('base_price', '29.99')
        ->set('unit', 'Piece')
        ->set('variants', [['variant_name' => 'Default', 'current_stock' => 10, 'low_stock_threshold' => 5, 'sku' => '']])
        ->set('image', UploadedFile::fake()->create('malware.php', 100, 'application/x-php'))
        ->call('save')
        ->assertHasErrors(['image']);
});

test('file larger than 5 mb is rejected by livewire upload validation', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(ProductManagement::class)
        ->set('name', 'Test Product')
        ->set('base_price', '29.99')
        ->set('unit', 'Piece')
        ->set('variants', [['variant_name' => 'Default', 'current_stock' => 10, 'low_stock_threshold' => 5, 'sku' => '']])
        ->set('image', UploadedFile::fake()->image('large.jpg', 3000, 3000)->size(6000))
        ->call('save')
        ->assertHasErrors(['image']);
});

test('image can be removed from product', function () {
    Storage::fake('public');

    $product = Product::create([
        'name' => 'Remove Image Product',
        'base_price' => 39.99,
        'unit' => 'Piece',
        'is_active' => true,
        'image_path' => 'products/test-image.jpg',
    ]);

    Storage::disk('public')->put('products/test-image.jpg', 'fake-content');

    $product->update(['image_path' => null]);

    $this->assertDatabaseHas('products', ['name' => 'Remove Image Product', 'image_path' => null]);
});

test('deleting product cleans up local image via livewire', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin']);

    $product = Product::create([
        'name' => 'Delete Image Product',
        'base_price' => 59.99,
        'unit' => 'Piece',
        'is_active' => true,
        'image_path' => 'products/to-delete.jpg',
    ]);

    Storage::disk('public')->put('products/to-delete.jpg', 'fake-content');

    Livewire::actingAs($admin)
        ->test(ProductManagement::class)
        ->call('delete', $product->id);

    Storage::disk('public')->assertMissing('products/to-delete.jpg');
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

test('products without images still work in product listing', function () {
    $product = Product::create([
        'name' => 'No Image Listing',
        'base_price' => 19.99,
        'unit' => 'Piece',
        'is_active' => true,
        'image_path' => null,
    ]);

    ProductVariant::create([
        'product_id' => $product->id,
        'variant_name' => 'Default',
        'current_stock' => 5,
        'low_stock_threshold' => 2,
    ]);

    $this->assertDatabaseHas('products', ['name' => 'No Image Listing', 'image_path' => null]);
    $this->assertNull($product->image_path);
});

test('product image can be replaced via livewire', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin']);

    $product = Product::create([
        'name' => 'Replace Image Product',
        'base_price' => 69.99,
        'unit' => 'Piece',
        'is_active' => true,
        'image_path' => 'products/old-image.jpg',
    ]);

    Storage::disk('public')->put('products/old-image.jpg', 'old-content');

    ProductVariant::create([
        'product_id' => $product->id,
        'variant_name' => 'Default',
        'current_stock' => 10,
        'low_stock_threshold' => 5,
    ]);

    Livewire::actingAs($admin)
        ->test(ProductManagement::class)
        ->call('edit', $product->id)
        ->set('image', UploadedFile::fake()->image('new-product.jpg', 200, 200)->size(100))
        ->call('save');

    Storage::disk('public')->assertMissing('products/old-image.jpg');
    $product->refresh();
    $this->assertStringStartsWith('products/', $product->image_path);
});

test('checkout can display product with image', function () {
    Storage::fake('public');

    $product = Product::create([
        'name' => 'Checkout Image Product',
        'base_price' => 25.00,
        'unit' => 'Piece',
        'is_active' => true,
        'image_path' => 'products/checkout-img.jpg',
    ]);

    ProductVariant::create([
        'product_id' => $product->id,
        'variant_name' => 'Default',
        'current_stock' => 10,
        'low_stock_threshold' => 5,
    ]);

    $this->assertDatabaseHas('products', ['name' => 'Checkout Image Product']);
    $this->assertNotNull($product->image_path);
});
