<?php

namespace Database\Seeders;

use App\Enum\IndicativeClassificationsEnum;
use App\Models\IndicativeRating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IndicateClassificationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $indicate_classifications = IndicativeClassificationsEnum::all();

        foreach ($indicate_classifications as $classification) {
            IndicativeRating::create($classification);
        }
    }
}
