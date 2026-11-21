<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SaleDetail; // เรียกใช้ SaleDetail Model
use App\Models\Customer;   // เรียกใช้ Customer Model

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_name',
        'payment_type',
        'total',
        'subtotal',
        'vat',
        'grand_total',
    ];

    public function saleDetails()
    {
        // Sale หนึ่งอัน มี SaleDetail ได้หลายอัน (hasMany)
        return $this->hasMany(SaleDetail::class);
    }

    public function customer()
    {
        // เราใช้ belongsTo เพราะ foreign key (customer_id) อยู่ในตารางนี้ ('sales')
        return $this->belongsTo(Customer::class);
    }
}