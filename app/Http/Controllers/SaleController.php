<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Exception;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user(); // ดึงข้อมูล user ที่ล็อกอินอยู่

            // ค้นหาหรือสร้างลูกค้าโดยใช้อีเมล
            $customer = Customer::firstOrCreate(
                ['email' => $user->email], // เงื่อนไขในการค้นหา
                ['name' => $user->name]    // ข้อมูลสำหรับสร้างใหม่ (ถ้าหาไม่เจอ)
            );

            // --- (ส่วนที่ลบออก) ไม่มีการจัดการแต้มส่วนลด ---

            // 1. สร้างใบเสร็จหลักในตาราง 'sales'
            $sale = new Sale();
            $sale->customer_id = $customer->id;
            $sale->customer_name = $customer->name;
            $sale->payment_type = $request->payment_type;
            $sale->total = $request->subtotal; // ยอดรวมก่อน VAT
            $sale->subtotal = $request->subtotal;
            $sale->vat = $request->vat;
            $sale->grand_total = $request->grand_total; // ใช้ยอดที่ส่งมาจาก JS โดยตรง
            // ไม่ต้องบันทึก points_used, points_discount
            $sale->save(); // บันทึกข้อมูลและรับ ID ของใบเสร็จใหม่

            // 2. วนลูปบันทึกรายการสินค้า และตัดสต็อก
            foreach ($request->items as $itemData) {
                // บันทึกรายการสินค้าในตาราง 'sale_items'
                $saleDetail = new SaleDetail();
                $saleDetail->sale_id = $sale->id;
                $saleDetail->product_id = $itemData['id'];
                $saleDetail->product_name = $itemData['name'];
                $saleDetail->qty = $itemData['qty'];
                $saleDetail->price = $itemData['price'];
                $saleDetail->total = $itemData['price'] * $itemData['qty'];
                $saleDetail->save();

                // ตัดสต็อกสินค้าในตาราง 'products'
                $product = Product::find($itemData['id']);
                if ($product) {
                    if($product->stock >= $itemData['qty']) {
                         $product->decrement('stock', $itemData['qty']);
                    } else {
                         throw new Exception("สินค้า {$product->name} มีสต็อกไม่เพียงพอ (คงเหลือ {$product->stock})");
                    }
                } else {
                     throw new Exception("ไม่พบสินค้า ID {$itemData['id']} ({$itemData['name']}) ในระบบ");
                }
            }

            // 3. คำนวณแต้มสะสมที่จะได้รับ (คำนวณจาก grand_total ที่จ่ายจริง)
            $pointsEarned = floor($sale->grand_total / 25); // 25 บาท ได้ 1 แต้ม
            if ($pointsEarned > 0) {
                $customer->increment('points', $pointsEarned);
            }

            // ถ้าทุกอย่างสำเร็จ ให้ยืนยันการบันทึกข้อมูลทั้งหมด
            DB::commit();

            // 4. ส่งคำตอบกลับไปให้ JavaScript ว่าสำเร็จ พร้อมกับ ID ของใบเสร็จ
            return response()->json([
                'success' => true,
                'sale_id' => $sale->id
            ]);

        } catch (Exception $e) {
            // ถ้าเกิดข้อผิดพลาดใดๆ ขึ้นมา ให้ยกเลิกการบันทึกทั้งหมด
            DB::rollBack();

            // ส่งคำตอบกลับไปให้ JavaScript ว่าล้มเหลว พร้อมกับข้อความ Error ที่แท้จริง
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() . ' in file ' . $e->getFile() . ' on line ' . $e->getLine()
            ], 500); // ส่ง Status Code 500 เพื่อบอกว่า Server Error
        }
    }

    public function receipt($id)
    {
        // ดึงข้อมูลการขายพร้อมรายละเอียดสินค้าและข้อมูลลูกค้า
        $sale = Sale::with('saleDetails.product', 'customer')->findOrFail($id);
        return view('sales.receipt', compact('sale'));
    }
}