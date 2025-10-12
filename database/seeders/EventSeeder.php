<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Get all spiritual guides
        $guides = User::where('user_type', 'spiritual_guide')->get();

        foreach ($guides as $guide) {
            for ($i = 1; $i <= 5; $i++) {
                Event::create([
                    'user_id' => $guide->id,
                    'title' => "Event $i by ".$guide->name,
                    'category' => 'Category '.rand(1, 5),
                    'date' => now()->addDays(rand(1, 30))->format('Y-m-d'),
                    'start_time' => now()->addHours(rand(1, 12))->format('H:i'),
                    'end_time' => now()->addHours(rand(13, 24))->format('H:i'),
                    'location' => 'Location '.rand(1, 10),
                    'description' => "This is a description for event $i by ".$guide->name,
                    'visibility' => collect(['public', 'private'])->random(),
                    'image' => null,
                ]);
            }
        }
    }
}
