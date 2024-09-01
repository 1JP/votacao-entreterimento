<?php

namespace Database\Seeders;

use App\Enum\TypesOfFilmsEnum;
use App\Models\CategoryType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types_films = TypesOfFilmsEnum::all();

        foreach ($types_films as $types) {
            CategoryType::create($types);
        }
        
    }
}
