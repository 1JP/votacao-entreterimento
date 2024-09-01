<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function type()
    {
        return $this->belongsTo(CategoryType::class, 'category_type_id');
    }

    public function setNameAttribute($value)
    {
        if (is_int($value)) {
            throw new \InvalidArgumentException('O nome não pode ser um número inteiro.');
        }

        if (strlen($value) > 45) {
            throw new \InvalidArgumentException('O nome não pode ter mais que 45 caracteres.');
        }

        $this->attributes['name'] = $value;
    }
}
