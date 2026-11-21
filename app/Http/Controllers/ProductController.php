<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        
        $customer = null;
        if (Auth::check()) {
            $user = Auth::user();
            $customer = Customer::where('email', $user->email)->first();
            if (!$customer) {
                 $customer = new Customer(['name' => $user->name, 'points' => 0]);
            }
        } else {
             $customer = new Customer(['name' => 'Guest', 'points' => 0]);
        }

        $customers_for_admin = Customer::all();

        return view('products.products', compact('products', 'customer', 'customers_for_admin'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $productData = $request->except('image');

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $productData['image'] = $imageName;
        }

        Product::create($productData);
        return redirect()->route('products.index')->with('success', 'เพิ่มสินค้าสำเร็จ');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
         $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $productData = $request->except('image');

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path('images/' . $product->image))) {
                @unlink(public_path('images/' . $product->image));
            }
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $productData['image'] = $imageName;
        }

        $product->update($productData);
        return redirect()->route('products.index')->with('success', 'อัปเดตสินค้าสำเร็จ');
    }

    public function destroy(Product $product)
    {
        if ($product->image && file_exists(public_path('images/' . $product->image))) {
             @unlink(public_path('images/' . $product->image));
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'ลบสินค้าสำเร็จ');
    }
}
