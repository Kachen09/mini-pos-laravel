<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('name', 'asc')->paginate(15); // แสดง 15 คนต่อหน้า
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email', // อีเมลไม่ซ้ำ (ถ้ามี)
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')
                         ->with('success', 'เพิ่มข้อมูลลูกค้าสำเร็จ');
    }

    public function show(Customer $customer)
    {
        $salesHistory = Sale::where('customer_id', $customer->id)
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('customers.show', compact('customer', 'salesHistory'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')
                         ->with('success', 'อัปเดตข้อมูลลูกค้าสำเร็จ');
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return redirect()->route('customers.index')
                             ->with('success', 'ลบข้อมูลลูกค้าสำเร็จ');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('customers.index')
                             ->with('error', 'ไม่สามารถลบลูกค้าได้ เนื่องจากมีประวัติการสั่งซื้ออยู่');
        }
    }
}