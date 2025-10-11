<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Only fetch users with user_type = 'spiritual_guide'
        $spiritualGuides = User::where('user_type', 'spiritual_guide')->get();

        foreach ($spiritualGuides as $user) {
            foreach ($daysOfWeek as $day) {
                DB::table('availabilities')->insert([
                    'user_id' => $user->id,
                    'date' => Carbon::now()->startOfWeek()->addDays(array_search($day, $daysOfWeek))->toDateString(),
                    'day' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'status' => 'available',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
