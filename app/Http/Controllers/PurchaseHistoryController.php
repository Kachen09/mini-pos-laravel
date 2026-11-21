<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sale;
use App\Models\Customer; // <-- **(เพิ่ม 1) เรียกใช้ Customer Model**

class PurchaseHistoryController extends Controller
{
    // ในไฟล์ app/Http/Controllers/PurchaseHistoryController.php

public function index()
{
    $user = Auth::user(); // 1. ดึงข้อมูล user ที่ล็อกอิน
    $sales = collect();  // 2. สร้าง $sales เปล่าๆ
    $customer = null;    // 3. สร้าง $customer เปล่าๆ

    // 4. ค้นหาข้อมูลลูกค้า (เราต้องการ $customer เพื่อเอาแต้ม)
    $customer = Customer::where('email', $user->email)->first();

    // 5. ถ้าหาลูกค้าเจอ ค่อยไปค้นหาประวัติการซื้อ
    if ($customer) {
        $sales = Sale::where('customer_id', $customer->id)
                    ->with('saleDetails.product')
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    // 6. **(ส่วนสำคัญ)** ส่งทั้ง $sales และ $customer ไปที่ View
    return view('products.history', compact('sales', 'customer'));
}
}