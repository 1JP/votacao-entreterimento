<?php

namespace Database\Seeders;

use App\Enum\RolesEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = RolesEnum::all();

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
