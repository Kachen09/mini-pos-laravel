<!DOCTYPE html>
<html lang="th">
<head>
    <title>รายงานยอดขาย</title>
    {{-- ใส่ CSS และ Head อื่นๆ ที่จำเป็น --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    {{-- อาจจะ @include sidebar ของคุณเข้ามา --}}
    
    <div class="container mt-4" id="main-content">
        <h1 class="mb-4">📈 รายงานยอดขาย</h1>

        {{-- ฟอร์มสำหรับกรองข้อมูล --}}
        <form method="GET" action="{{ route('reports.sales') }}" class="mb-4">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label for="filter" class="form-label">เลือกช่วงเวลา:</label>
                    <select name="filter" id="filter" class="form-select">
                        <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>วันนี้</option>
                        <option value="this_month" {{ $filter == 'this_month' ? 'selected' : '' }}>เดือนนี้</option>
                        <option value="this_year" {{ $filter == 'this_year' ? 'selected' : '' }}>ปีนี้</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">ดูรายงาน</button>
                </div>
            </div>
        </form>

        {{-- สรุปยอดขายรวม --}}
        <div class="alert alert-success">
            <h4>ยอดขายรวม: {{ number_format($totalSales, 2) }} บาท</h4>
        </div>

        {{-- ตารางแสดงรายการขาย --}}
        <div class="card">
            <div class="card-header">
                รายการขาย
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>เลขที่ใบเสร็จ</th>
                            <th>ชื่อลูกค้า</th>
                            <th>วันที่</th>
                            <th class="text-end">ยอดรวมสุทธิ (บาท)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr>
                                <td>#{{ $sale->id }}</td>
                                <td>{{ $sale->customer_name }}</td>
                                <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end">{{ number_format($sale->grand_total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">ไม่พบข้อมูลการขายในช่วงเวลานี้</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">กลับหน้าหลัก</a>
    </div>
</body>
</html>