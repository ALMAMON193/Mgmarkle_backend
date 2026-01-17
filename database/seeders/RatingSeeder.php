<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        // Get all seekers
        $seekers = User::where('user_type', 'seeker')->get();

        // Get all events
        $events = Event::all();

        foreach ($events as $event) {
            // Pick 2-5 random seekers to rate this event
            $randomSeekers = $seekers->random(rand(2, min(5, $seekers->count())));

            foreach ($randomSeekers as $seeker) {
                Rating::create([
                    'user_id' => $event->user_id, // event owner
                    'event_id' => $event->id,
                    'rating' => rand(10, 50) / 10, // 1.0 to 5.0
                    'comment' => 'Sample comment by '.$seeker->name,
                ]);
            }
        }
    }
}
