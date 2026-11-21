<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ใบเสร็จรับเงิน เลขที่ #{{ $sale->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* เพิ่มสไตล์เล็กน้อยเพื่อไม่ให้ติดขอบจอเกินไป */
        body {
            font-family: 'Sarabun', sans-serif; /* แนะนำให้ใช้ Font ภาษาไทย */
        }
        @media print {
            /* ซ่อนปุ่มตอนสั่งพิมพ์ */
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="p-4 p-md-5">
    <div class="container border p-4 shadow-sm" style="max-width: 800px;">
        <h3>🧾 ใบเสร็จรับเงิน</h3>
        <p>ร้าน POS System</p>
        <hr>

        <p><strong>เลขที่ใบเสร็จ:</strong> #{{ $sale->id }}</p>
        <p><strong>ชื่อลูกค้า:</strong> {{ $sale->customer_name }}</p>
        <p><strong>วันที่:</strong> {{ $sale->created_at->format('d/m/Y H:i:s') }}</p>
        <p><strong>ช่องทางชำระเงิน:</strong> {{ ucfirst($sale->payment_type) }}</p>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>สินค้า</th>
                    <th class="text-center">จำนวน</th>
                    <th class="text-end">ราคา/หน่วย</th>
                    <th class="text-end">รวม</th>
                </tr>
            </thead>
            <tbody>
                {{-- ▼▼▼ (จุดสำคัญ) แก้ไขจาก $sale->items เป็น $sale->saleDetails ▼▼▼ --}}
                @foreach($sale->saleDetails as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td class="text-center">{{ $item->qty }}</td>
                    <td class="text-end">{{ number_format($item->price, 2) }}</td>
                    <td class="text-end">{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row">
            <div class="col-6 offset-6">
                <p class="d-flex justify-content-between">
                    <span>ยอดก่อน VAT:</span>
                    <span>{{ number_format($sale->subtotal, 2) }} บาท</span>
                </p>
                <p class="d-flex justify-content-between">
                    <span>VAT 7%:</span>
                    <span>{{ number_format($sale->vat, 2) }} บาท</span>
                </p>
                <hr>
                <h4 class="d-flex justify-content-between">
                    <span>ยอดรวมสุทธิ:</span>
                    <span>{{ number_format($sale->grand_total, 2) }} บาท</span>
                </h4>
            </div>
        </div>

        <div class="mt-4 text-center no-print">
            <button onclick="window.print()" class="btn btn-secondary">🖨️ พิมพ์ใบเสร็จ</button>
            <a href="{{ route('products.index') }}" class="btn btn-primary">กลับหน้าขายสินค้า</a>
        </div>
    </div>
</body>
</html>