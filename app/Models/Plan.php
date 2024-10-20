<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function setNameAttribute($value)
    {
        if (is_int($value)) {
            throw new \InvalidArgumentException('O nome não pode ser um número inteiro.');
        }

        if (strlen($value) > 65) {
            throw new \InvalidArgumentException('O nome não pode ter mais que 65 caracteres.');
        }

        $this->attributes['name'] = $value;
    }

    public function setDescriptionAttribute($value)
    {
        if (is_int($value)) {
            throw new \InvalidArgumentException('A descrição não pode ser um número inteiro.');
        }

        if (strlen($value) > 250) {
            throw new \InvalidArgumentException('A descrição não pode ter mais que 250 caracteres.');
        }

        $this->attributes['description'] = $value;
    }

    public function setNumberFilmAttribute($value)
    {
        if (is_string($value)) {
            throw new \InvalidArgumentException('O número de filmes não pode ser uma string.');
        }

        $this->attributes['number_film'] = $value;
    }

    public function setNumberBookAttribute($value)
    {
        if (is_string($value)) {
            throw new \InvalidArgumentException('O número de livros não pode ser uma string.');
        }

        $this->attributes['number_book'] = $value;
    }

    public function setNumberSerieAttribute($value)
    {
        if (is_string($value)) {
            throw new \InvalidArgumentException('O número de séries não pode ser uma string.');
        }

        $this->attributes['number_serie'] = $value;
    }

    public function setValueAttribute($value)
    {
        if (is_int($value) || is_string($value)) {
            throw new \InvalidArgumentException('O número de séries não pode ser uma string/inteiro.');
        }

        $this->attributes['value'] = $value;
    }

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

    public function setActiveAttribute($value)
    {
        if (is_string($value)) {
            throw new \InvalidArgumentException('O nome não pode ser uma string.');
        }

        $this->attributes['active'] = $value;
    }
}
