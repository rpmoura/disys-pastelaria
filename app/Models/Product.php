<?php

namespace App\Models;

class Product extends TableModelAbstract
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'photo',
        'price',
    ];
}
