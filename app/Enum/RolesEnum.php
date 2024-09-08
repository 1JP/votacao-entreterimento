<?php

namespace App\Enum;

class RolesEnum
{

    public static $data = [
        [
            'name' => 'Admin',
            'guard_name' => 'web',
        ],
        [
            'name' => 'Membros',
            'guard_name' => 'web',
        ],
        [
            'name' => 'Usuario',
            'guard_name' => 'web',
        ],
        [
            'name' => 'Root',
            'guard_name' => 'web',
        ],
    ];

    public static function all() {
        return self::$data;
    }
}
