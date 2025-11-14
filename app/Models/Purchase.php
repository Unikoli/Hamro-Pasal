<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    //  protected $fillable = ['supplier_id','total_cost'];
     protected $fillable = [
        'product_id', 'supplier_id', 'quantity', 'purchase_price', 'purchase_date', 'user_id'
    ];
      public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function items() { return $this->hasMany(PurchaseItem::class); }
}
