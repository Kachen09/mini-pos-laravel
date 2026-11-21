<!DOCTYPE html>
<html lang="th">
<head>
    <title>รายละเอียดลูกค้า: {{ $customer->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-3">รายละเอียดลูกค้า</h1>
        <div class="card mb-4">
            <div class="card-header">
                ข้อมูลลูกค้า #{{ $customer->id }}
            </div>
            <div class="card-body">
                <p><strong>ชื่อ:</strong> {{ $customer->name }}</p>
                <p><strong>อีเมล:</strong> {{ $customer->email ?? '-' }}</p>
            </div>
            <div class="card-footer">
                 <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit me-1"></i> แก้ไข</a>
                 <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> กลับไปที่รายชื่อ</a>
            </div>
        </div>

        <h3 class="mb-3">ประวัติการสั่งซื้อ</h3>
        <div class="card">
             <div class="card-body">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>เลขที่ใบเสร็จ</th>
                            <th>วันที่</th>
                            <th class="text-end">ยอดรวม</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($salesHistory as $sale)
                            <tr>
                                <td>#{{ $sale->id }}</td>
                                <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end">{{ number_format($sale->grand_total, 2) }} ฿</td>
                                <td>
                                    <a href="{{ route('sales.receipt', $sale->id) }}" class="btn btn-outline-primary btn-sm" target="_blank">ดูใบเสร็จ</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">ยังไม่มีประวัติการสั่งซื้อ</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                 {{-- แสดง Pagination Links สำหรับประวัติ --}}
                 <div class="d-flex justify-content-center mt-3">
                    {{ $salesHistory->links() }}
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>