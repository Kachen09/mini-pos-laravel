<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // ✅ import ให้ถูก
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'lot_number',
        'expired_date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
