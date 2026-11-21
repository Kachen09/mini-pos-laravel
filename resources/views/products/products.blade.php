<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS System</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<button id="menu-btn">&#9776;</button>

<div id="sidebar">
    <a href="{{ route('products.index') }}">📦 สินค้าทั้งหมด</a>
    @if(auth()->check() && auth()->user()->role == 'admin')
        <a href="{{ route('products.create') }}">➕ เพิ่มสินค้า</a>
        <a href="{{ route('reports.sales') }}">📈 รายงานยอดขาย</a>
        <a href="{{ route('reports.best_sellers') }}">📊 สินค้าขายดี</a>
        <a href="{{ route('reports.low_stock') }}">⚠️ สินค้าใกล้หมด</a>
        <a href="{{ route('customers.index') }}">👥 จัดการลูกค้า</a>
        <a href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-1"></i> Dashboard</a>
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

<div class="container-fluid mt-4" id="main-content">
    <div class="row">
        {{-- ส่วนแสดงรายการสินค้า --}}
        <div class="col-md-8">
            <div class="row">
                @foreach($products as $product)
                    <div class="col-6 col-md-4 col-lg-3 mb-3">
                        <div class="card product-card position-relative h-100">
                           <span class="position-absolute top-0 end-0 bg-light border rounded-pill px-2 m-2 d-flex align-items-center" style="font-size: 0.9em;">
                                {{ $product->stock }}
                                @if(auth()->check() && auth()->user()->role == 'admin')
                                    @if($product->stock <= $product->reorder_level)
                                        <i class="fas fa-exclamation-triangle text-danger ms-1" title="สินค้าใกล้หมดสต็อก!"></i>
                                    @endif
                                @endif
                            </span>
                            @if($product->image)
                                <img src="{{ asset('images/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height:150px; object-fit:cover;">
                            @else
                                <img src="https://via.placeholder.com/150x150?text=No+Image" class="card-img-top" alt="No Image">
                            @endif
                            <div class="card-body text-center d-flex flex-column">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">{{ number_format($product->price, 2) }} ฿</p>
                                <div class="mt-auto">
                                    <button class="btn btn-success btn-sm add-to-cart mb-1"
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-price="{{ $product->price }}">
                                        🛒 เพิ่ม
                                    </button>
                                    @if(auth()->check() && auth()->user()->role == 'admin')
                                        <div class="mt-1">
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">✏️</a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลบสินค้า?')">🗑️</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ส่วนของตะกร้าสินค้า --}}
        <div class="col-md-4">
            <div id="cart" class="sticky-top">
                <h4>🛍️ ตะกร้าสินค้า</h4>
                <ul class="list-group mb-3" id="cart-list" style="min-height: 100px;"></ul>
                <h5 class="text-end">รวม: <span id="total">0.00</span> ฿</h5>
                <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                    💰 ชำระเงิน
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ส่วน Modal (ป็อปอัพ - แก้ไขแล้ว เอาส่วนแลกแต้มออก + เพิ่มเลขบัญชี + เพิ่ม QR Code) --}}
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">สรุปการชำระเงิน</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- ส่วนแสดงชื่อลูกค้า --}}
                <div class="mb-3">
                    <label class="form-label">ชื่อลูกค้า:</label>
                    <input type="text" class="form-control" id="modal_customer_name" value="{{ $customer->name ?? auth()->user()->name }}" readonly>
                    <input type="hidden" id="customer_id_hidden_for_js" value="{{ $customer->id ?? auth()->user()->id }}">
                </div>

                {{-- แสดงแต้มปัจจุบันอย่างเดียว --}}
                <div class="mb-3 border rounded p-3 bg-light-subtle">
                    <p class="mb-0">
                        <i class="fas fa-star text-warning me-1"></i>
                        <strong>แต้มสะสมปัจจุบัน:</strong>
                        <span id="current_points">{{ $customer->points ?? 0 }}</span> คะแนน
                    </p>
                </div>

                {{-- ส่วนสรุปยอด --}}
                <p>ยอดรวมสินค้า: <span id="subtotal">0.00</span> ฿</p>
                <p>VAT 7%: <span id="vat">0.00</span> ฿</p>
                <h5 class="mt-2">ยอดรวมสุทธิ: <span id="grand_total" class="text-success fw-bold">0.00</span> ฿</h5>
                <hr>
                <label>เลือกช่องทางการชำระเงิน:</label>
                <select id="payment_type" class="form-select">
                    <option value="cash">💵 เงินสด</option>
                    <option value="bank">🏦 โอนธนาคาร</option>
                    <option value="qr">📱 QR พร้อมเพย์</option>
                </select>
                {{-- ส่วนแสดงเลขบัญชี --}}
                <div id="bankAccountInfo" class="mt-3 alert alert-secondary" style="display: none;">
                    <strong>กรุณาโอนเงินมาที่:</strong><br>
                    ธนาคาร: ธนาคารกสิกร <img src="{{ asset('images/kg.png') }}" alt="K Back" class="img-fluid mt-2" style="max-width: 20px;"><br>
                    เลขที่บัญชี: 157-19xx-xxx<br>
                    ชื่อบัญชี: นายคเชนพ์ กอนไชย
                </div>
                {{-- ส่วนแสดง QR Code --}}
                <div id="qrCodeInfo" class="mt-3 text-center" style="display: none;">
                    <strong>สแกน QR Code เพื่อชำระเงิน (พร้อมเพย์)</strong><br>
                    <img src="{{ asset('images/promptpay_qr.jpg') }}" alt="PromptPay QR Code" class="img-fluid mt-2" style="max-width: 200px;">
                    <p class="mt-2 text-muted">กรุณากรอกยอด: <strong id="qrAmount">0.00</strong> บาท</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" id="confirmPayment" class="btn btn-success">ยืนยันชำระเงิน</button>
            </div>
        </div>
    </div>
</div>


{{-- โค้ด JAVASCRIPT (ฉบับเต็ม ล่าสุด + แสดง QR Code) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // --- Sidebar ---
        const menuBtn = document.getElementById("menu-btn");
        const sidebar = document.getElementById("sidebar");
        if (menuBtn && sidebar) {
            menuBtn.addEventListener("click", function() {
                sidebar.classList.toggle("active");
            });
        }

        // --- Cart Logic ---
        let cart = []; // Array เก็บสินค้าในตะกร้า
        const cartList = document.getElementById("cart-list"); // UL ที่แสดงรายการ
        const totalDisplay = document.getElementById("total"); // Span ที่แสดงยอดรวม

        // ฟังก์ชันวาดตะกร้าใหม่
        function renderCart() {
            if (!cartList || !totalDisplay) return; // เช็คว่า element มีจริงไหม
            cartList.innerHTML = ""; // ล้างรายการเก่า
            let currentTotal = 0;
            if (cart.length === 0) {
                cartList.innerHTML = '<li class="list-group-item text-muted text-center">ไม่มีสินค้าในตะกร้า</li>';
            } else {
                cart.forEach((item, index) => {
                    currentTotal += item.price * item.qty;
                    const li = document.createElement("li");
                    li.className = "list-group-item d-flex justify-content-between align-items-center";
                    li.innerHTML = `
                        <div>${item.name}<br><small class="text-muted">${item.qty} x ${item.price.toFixed(2)}</small></div>
                        <div><span class="fw-bold me-2">${(item.price * item.qty).toFixed(2)}</span>
                        <button type="button" class="btn btn-danger btn-sm remove-item" data-index="${index}">&times;</button></div>`;
                    cartList.appendChild(li);
                });
            }
            totalDisplay.innerText = currentTotal.toFixed(2); // อัปเดตยอดรวมท้ายตะกร้า
        }

        // ทำให้ปุ่ม "เพิ่ม" สินค้าทำงาน
        document.querySelectorAll(".add-to-cart").forEach(button => {
            button.addEventListener("click", function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const price = parseFloat(this.dataset.price);
                const existingItem = cart.find(item => item.id === id);
                if (existingItem) {
                    existingItem.qty++;
                } else {
                    cart.push({ id, name, price, qty: 1 });
                }
                renderCart();
            });
        });

        // ทำให้ปุ่มลบ (x) ในตะกร้าทำงาน
        if (cartList) {
            cartList.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-item')) {
                    const index = event.target.dataset.index;
                    cart.splice(index, 1);
                    renderCart();
                }
            });
        }

        // --- Checkout Modal Logic ---
        const checkoutModal = document.getElementById('checkoutModal');
        const currentPointsDisplay = document.getElementById("current_points");
        const confirmPaymentBtn = document.getElementById('confirmPayment');
        const paymentTypeSelect = document.getElementById('payment_type');
        const bankAccountInfoDiv = document.getElementById('bankAccountInfo');
        const qrCodeInfoDiv = document.getElementById('qrCodeInfo'); // <-- เพิ่ม element QR Div
        const qrAmountSpan = document.getElementById('qrAmount');     // <-- เพิ่ม element ยอด QR
        let customerPoints = 0;

        // --- ตอนที่ Modal กำลังจะเปิด ---
        if (checkoutModal) {
            checkoutModal.addEventListener('show.bs.modal', function() {
                // อ่านค่าแต้มล่าสุด
                customerPoints = parseInt(currentPointsDisplay.innerText) || 0;

                // คำนวณยอดรวม และแสดงผล
                let subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                let vat = subtotal * 0.07;
                let grandTotal = subtotal + vat;
                document.getElementById("subtotal").innerText = subtotal.toFixed(2);
                document.getElementById("vat").innerText = vat.toFixed(2);
                document.getElementById("grand_total").innerText = grandTotal.toFixed(2);
                qrAmountSpan.innerText = grandTotal.toFixed(2); // **(เพิ่ม)** อัปเดตยอด QR

                // เช็คค่าเริ่มต้นของ payment type เพื่อซ่อน/แสดงเลขบัญชีและ QR
                handlePaymentTypeChange();
            });
        }

        // --- ฟังก์ชันแสดง/ซ่อน เลขบัญชี/QR ตาม Payment Type ---
        function handlePaymentTypeChange() {
            if (!paymentTypeSelect || !bankAccountInfoDiv || !qrCodeInfoDiv) return; // เช็ค element ก่อน
            const selectedValue = paymentTypeSelect.value;
            if (selectedValue === 'bank') {
                bankAccountInfoDiv.style.display = 'block'; // แสดงเลขบัญชี
                qrCodeInfoDiv.style.display = 'none';  // ซ่อน QR
            } else if (selectedValue === 'qr') {
                bankAccountInfoDiv.style.display = 'none'; // ซ่อนเลขบัญชี
                qrCodeInfoDiv.style.display = 'block'; // แสดง QR
            } else { // กรณี cash หรืออื่นๆ
                bankAccountInfoDiv.style.display = 'none'; // ซ่อนเลขบัญชี
                qrCodeInfoDiv.style.display = 'none';  // ซ่อน QR
            }
        }

        // --- ทำให้ dropdown ชำระเงิน เรียกใช้ฟังก์ชัน handlePaymentTypeChange ---
        if (paymentTypeSelect) {
            paymentTypeSelect.addEventListener('change', handlePaymentTypeChange);
        }

        // --- ตอนกดยืนยันชำระเงิน ---
        if (confirmPaymentBtn) {
            confirmPaymentBtn.addEventListener('click', async function() {
                this.disabled = true;
                this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> กำลังบันทึก...';

                const customerIdEl = document.getElementById('customer_id_hidden_for_js');
                const paymentTypeEl = document.getElementById('payment_type');

                let subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                let vat = subtotal * 0.07;
                let grandTotal = subtotal + vat;

                const payload = {
                    customer_id: customerIdEl ? customerIdEl.value : null,
                    payment_type: paymentTypeEl ? paymentTypeEl.value : 'cash',
                    items: cart,
                    subtotal: subtotal,
                    vat: vat,
                    grand_total: grandTotal
                };

                if (cart.length === 0) {
                    alert("กรุณาเพิ่มสินค้าลงในตะกร้าก่อน");
                    this.disabled = false; this.innerText = 'ยืนยันชำระเงิน'; return;
                }

                try {
                    const response = await fetch('{{ route("sales.store") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                        body: JSON.stringify(payload)
                    });
                    const data = await response.json();
                    if (response.ok && data.success) {
                        alert('บันทึกการขายสำเร็จ!');
                        window.location.href = `/sales/receipt/${data.sale_id}`;
                    } else {
                        alert('เกิดข้อผิดพลาด: \n' + (data.message || 'ไม่สามารถบันทึกข้อมูลได้'));
                    }
                } catch (error) {
                    console.error('Fetch Error:', error);
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                } finally {
                     this.disabled = false; this.innerText = 'ยืนยันชำระเงิน';
                }
            });
        }
    }); // ปิด DOMContentLoaded
</script>

</body>
</html>

