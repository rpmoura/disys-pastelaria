<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Casts\Attribute};

class Client extends TableModelAbstract
{
    protected $table = 'clients';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'birth_date',
        'address',
        'neighborhood',
        'add_on',
        'postcode',
    ];

    protected function postcode(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => preg_replace('/[^0-9]/', '', $value),
        );
    }
}
