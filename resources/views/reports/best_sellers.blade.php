<!DOCTYPE html>
<html lang="th">
<head>
    <title>รายงานสินค้าขายดี</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
</head>
<body>
    <div class="container report-container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-trophy text-warning me-2"></i>
                10 อันดับสินค้าขายดี
            </h1>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="text-center rank-column">อันดับ</th>
                                <th colspan="2">ชื่อสินค้า</th>
                                <th class="text-end">จำนวนที่ขายได้ (ชิ้น)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($bestSellers->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center p-4 text-muted">
                                        ยังไม่มีข้อมูลการขาย
                                    </td>
                                </tr>
                            @else
                                @foreach($bestSellers as $item)
                                    <tr>
                                        <td class="text-center rank-column">{{ $loop->iteration }}</td>
                                        
                                        <td style="width: 80px;">
                                            @if($item->image)
                                                {{-- ▼▼▼ แก้ไขบรรทัดนี้โดยเพิ่ม style เข้าไปโดยตรง ▼▼▼ --}}
                                                <img src="{{ asset('images/' . $item->image) }}" alt="{{ $item->product_name }}" class="product-image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                            @else
                                                <img src="https://via.placeholder.com/50x50?text=No+Img" alt="No Image" class="product-image bg-light" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                            @endif
                                        </td>
                                        {{-- ▲▲▲ สิ้นสุดส่วนที่แก้ไข ▲▲▲ --}}

                                        <td>{{ $item->product_name }}</td>
                                        <td class="text-end fw-bold">{{ number_format($item->total_sold) }}</td>
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