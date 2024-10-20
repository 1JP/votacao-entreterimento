<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function setCustomerIdAttribute($value)
    {
        if (is_int($value)) {
            throw new \InvalidArgumentException('O nome não pode ser um número inteiro.');
        }

        if (strlen($value) > 100) {
            throw new \InvalidArgumentException('O nome não pode ter mais que 100 caracteres.');
        }

        $this->attributes['customer_id'] = $value;
    }
}
