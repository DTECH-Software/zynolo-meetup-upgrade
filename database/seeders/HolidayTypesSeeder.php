<?php

namespace Database\Seeders;

use App\Models\Day;
use App\Models\HolidayType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HolidayTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HolidayType::create(['type'=>'DAY']);
        HolidayType::create(['type'=>'WEEKEND']);
        HolidayType::create(['type'=>'SATURDAY']);
        HolidayType::create(['type'=>'SUNDAY']);
    }
}
