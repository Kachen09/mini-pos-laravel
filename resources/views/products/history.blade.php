<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการซื้อ</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<button id="menu-btn" class="btn btn-dark">&#9776;</button>
<div id="sidebar">
    <a href="{{ route('products.index') }}">📦 สินค้าทั้งหมด</a> {{-- เพิ่มลิงก์กลับหน้าหลัก --}}
    @if(auth()->check() && auth()->user()->role == 'admin')
        <a href="{{ route('products.create') }}">➕ เพิ่มสินค้า</a>
        <a href="{{ route('reports.sales') }}">📈 รายงานยอดขาย</a>
        <a href="{{ route('reports.best_sellers') }}">📊 สินค้าขายดี</a>
        <a href="{{ route('reports.low_stock') }}">⚠️ สินค้าใกล้หมด</a>
        <a href="{{ route('customers.index') }}">👥 จัดการลูกค้า</a>
    @endif
    <a href="{{ route('products.history') }}">📜 ประวัติการซื้อ</a>
    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>
<div id="main-content">
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">

                <h2 class="mb-3 text-center">ประวัติการซื้อของคุณ</h2>

                <div class="alert alert-info text-center shadow-sm mb-4">
                    <i class="fas fa-star text-warning me-2"></i>
                    <strong>แต้มสะสมปัจจุบัน:</strong>
                    {{ $customer->points ?? 0 }} คะแนน
                </div>

                @forelse($sales as $sale)
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-light d-flex flex-wrap justify-content-between align-items-center">
                            <div>
                                <strong class="me-3">คำสั่งซื้อเลขที่: #{{ $sale->id }}</strong>
                                <span class="text-muted">วันที่: {{ $sale->created_at->format('d/m/Y เวลา H:i น.') }}</span>
                            </div>
                            <strong class="fs-5 text-success mt-2 mt-md-0">ยอดรวม: {{ number_format($sale->grand_total, 2) }} ฿</strong>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%;" class="ps-3">รูปภาพ</th>
                                            <th>ชื่อสินค้า</th>
                                            <th class="text-center">จำนวน</th>
                                            <th class="text-end pe-3">ราคารวม</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sale->saleDetails as $detail)
                                            <tr>
                                                <td class="ps-3">
                                                    @if($detail->product && $detail->product->image)
                                                        <img src="{{ asset('images/' . $detail->product->image) }}" alt="{{ $detail->product_name ?? ($detail->product->name ?? '') }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                                    @else
                                                        <img src="https://via.placeholder.com/60x60?text=No+Img" alt="No Image" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $detail->product_name ?? ($detail->product->name ?? 'สินค้าถูกลบไปแล้ว') }}<br>
                                                    <small class="text-muted">ราคา: {{ number_format($detail->price, 2) }} ฿</small>
                                                </td>
                                                <td class="text-center">{{ $detail->qty }}</td>
                                                <td class="text-end pe-3">{{ number_format($detail->total, 2) }} ฿</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                             <a href="{{ route('sales.receipt', $sale->id) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                                 <i class="fas fa-receipt me-1"></i> ดูใบเสร็จ
                             </a>
                        </div>
                    </div>
                @empty
                    <div class="alert alert-light text-center border shadow-sm">
                        <p class="mb-0">คุณยังไม่มีประวัติการสั่งซื้อ</p>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</div>

{{-- Script สำหรับ Sidebar --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const menuBtn = document.getElementById("menu-btn");
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("main-content");

    if (menuBtn && sidebar && mainContent) {
        menuBtn.addEventListener("click", function() {
            sidebar.classList.toggle("active");
        });
    }
</script>

</body>
</html>