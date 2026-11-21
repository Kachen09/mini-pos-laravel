<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class SaleDetail extends Model
{
    use HasFactory;

    protected $table = 'sale_items';

    public function product()
    {
        // เราใช้ belongsTo เพราะ foreign key (product_id) อยู่ในตารางนี้
        return $this->belongsTo(Product::class);
    }
}