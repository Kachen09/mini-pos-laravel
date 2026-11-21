<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มสินค้าใหม่</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="p-4">
    <div class="container">
        <h1 class="mb-4">➕ เพิ่มสินค้าใหม่</h1>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="sku" class="form-label">SKU:</label>
                <input type="text" name="sku" id="sku" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">ชื่อสินค้า:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">ราคา:</label>
                <input type="number" name="price" id="price" step="0.01" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">จำนวนสต็อก:</label>
                <input type="number" name="stock" id="stock" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="reorder_level" class="form-label">จุดแจ้งเตือนสินค้าใกล้หมด (ชิ้น)</label>
                <input type="number" class="form-control" id="reorder_level" name="reorder_level" value="5" required>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">รูปภาพสินค้า:</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">สร้างสินค้า</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">ย้อนกลับ</a>
            </div>
        </form>
    </div>
</body>
</html>