<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};

class Order extends TableModelAbstract
{
    protected $table = 'orders';

    protected $fillable = [
        'client_id',
        'total',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'orders_x_products', 'order_id', 'product_id');
    }
}
