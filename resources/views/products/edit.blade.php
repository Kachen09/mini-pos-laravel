<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>
    <div class="form-container">
        <h1>Edit Product</h1>
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label for="sku">SKU:</label>
            <input type="text" name="sku" id="sku" value="{{ $product->sku }}" required>

            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="{{ $product->name }}" required>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" value="{{ $product->price }}" required>

            <label for="stock">Stock:</label>
            <input type="number" name="stock" id="stock" value="{{ $product->stock }}" required>

            <label for="reorder_level">Reorder Level:</label>
            <input type="number" name="reorder_level" id="reorder_level" value="{{ $product->reorder_level }}" required>

            <label>Product Image</label>
            @if($product->image)
                <div>
                    <img src="{{ asset('images/'.$product->image) }}" width="100" class="mb-2">
                </div>
            @endif
            <input type="file" name="image">

            <div class="form-actions">
                <button type="submit" class="btn btn-submit">Update</button>
                <a href="{{ route('products.index') }}" class="btn btn-back">Back</a>
            </div>
        </form>
    </div>
</body>
</html>