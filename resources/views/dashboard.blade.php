<!DOCTYPE html>
<html lang="th">
<head>
    <title>Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}"> --}}

</head>
<body>
    <div class="container-fluid mt-4" id="main-content"> {{-- ใช้ container-fluid ถ้ามี sidebar --}}

        <h1 class="mb-4"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</h1>

        <div class="row g-4">
            <div class="col-md-6 col-xl-3">
                <div class="card shadow-sm dashboard-card h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-success">ยอดขายวันนี้</h5>
                            <p class="card-text fs-4 fw-bold">{{ number_format($todaySalesTotal, 2) }} ฿</p>
                        </div>
                        <i class="fas fa-coins text-success card-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card shadow-sm dashboard-card h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-primary">จำนวนบิลวันนี้</h5>
                            <p class="card-text fs-4 fw-bold">{{ number_format($todayOrdersCount) }}</p>
                        </div>
                         <i class="fas fa-file-invoice-dollar text-primary card-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card shadow-sm dashboard-card h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-info">ยอดขายเดือนนี้</h5>
                            <p class="card-text fs-4 fw-bold">{{ number_format($monthSalesTotal, 2) }} ฿</p>
                        </div>
                        <i class="fas fa-calendar-alt text-info card-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card shadow-sm dashboard-card h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-warning">จำนวนบิลเดือนนี้</h5>
                            <p class="card-text fs-4 fw-bold">{{ number_format($monthOrdersCount) }}</p>
                        </div>
                         <i class="fas fa-calendar-check text-warning card-icon"></i>
                    </div>
                </div>
            </div>
             <div class="col-md-6 col-xl-3">
                <a href="{{ route('reports.low_stock') }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm dashboard-card h-100 {{ $lowStockCount > 0 ? 'border-danger' : '' }}">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title text-danger">สินค้าใกล้หมด</h5>
                                <p class="card-text fs-4 fw-bold">{{ number_format($lowStockCount) }} รายการ</p>
                            </div>
                            <i class="fas fa-exclamation-triangle text-danger card-icon"></i>
                        </div>
                    </div>
                </a>
            </div>
             <div class="col-md-6 col-xl-3">
                 {{-- Placeholder --}}
             </div>

            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-dark text-white">
                       <i class="fas fa-star me-2"></i> สินค้าขายดี (วันนี้)
                    </div>
                    <div class="card-body">
                        @if($todayBestSellers->isEmpty())
                            <p class="text-muted text-center">ยังไม่มีข้อมูลการขายสำหรับวันนี้</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($todayBestSellers as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $loop->iteration }}. {{ $item->product_name }}</span>
                                        <span class="badge bg-primary rounded-pill">{{ number_format($item->total_sold) }} ชิ้น</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="text-center mt-2">
                             <a href="{{ route('reports.best_sellers') }}" class="btn btn-sm btn-outline-secondary">ดูทั้งหมด</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                 {{-- Placeholder --}}
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