<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total_amount', 'status', 'shipping_address', 'invoice_file'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
