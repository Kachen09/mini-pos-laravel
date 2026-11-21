<!DOCTYPE html>
<html lang="th">
<head>
    <title>รายงานสินค้าใกล้หมดสต็อก</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
</head>
<body>
    <div class="container report-container my-5">
        <div class="report-header d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                รายงานสินค้าใกล้หมดสต็อก
            </h1>
            <span class="badge bg-danger rounded-pill">{{ $lowStockProducts->count() }} รายการ</span>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 80px;">รูปภาพ</th>
                                <th>ชื่อสินค้า</th>
                                <th class="text-center">สินค้าทั้งหมด</th>
                                <th class="text-center">สินค้าที่ขายไป</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($lowStockProducts->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center p-4 text-muted">
                                        <i class="fas fa-check-circle text-success me-2"></i> ไม่มีสินค้าใกล้หมดสต็อก
                                    </td>
                                </tr>
                            @else
                                @foreach($lowStockProducts as $product)
                                    <tr>
                                        <td>
                                            @if($product->image)
                                                {{-- ▼▼▼ Add style directly here ▼▼▼ --}}
                                                <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="product-image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                            @else
                                                <img src="https://via.placeholder.com/50x50?text=No+Img" alt="No Image" class="product-image bg-light" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                            @endif
                                            {{-- ▲▲▲ End of changes ▲▲▲ --}}
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td class="text-center fw-bold text-danger">{{ $product->stock }}</td>
                                        <td class="text-center text-muted">{{ $product->reorder_level }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> กลับหน้าหลัก
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>