<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $guides = User::where('user_type', 'spiritual_guide')->get();

        if ($guides->isEmpty()) {
            return;
        }

        $sampleEvents = [
            [
                'title' => 'Weekly Group Prayer & Meditation',
                'category' => 'Prayer & Worship',
                'location' => 'Grace Fellowship Main Hall',
                'description' => 'Join us for an uplifting group prayer session, reflection, and guided spiritual meditation.',
            ],
            [
                'title' => 'Spiritual Guidance & Growth Workshop',
                'category' => 'Spiritual Growth',
                'location' => 'Sanctuary Community Center',
                'description' => 'An interactive workshop focusing on practical spiritual growth, faith strengthening, and personal counsel.',
            ],
            [
                'title' => 'Faith, Healing & Hope Gathering',
                'category' => 'Healing & Hope',
                'location' => 'Hope Chapel Auditorium',
                'description' => 'A special gathering dedicated to emotional healing, prayer requests, and community support.',
            ],
            [
                'title' => 'Youth & Young Adult Devotional Circle',
                'category' => 'Youth Ministry',
                'location' => 'Online Video Session',
                'description' => 'An engaging open discussion for young adults seeking guidance on life questions and faith.',
            ],
            [
                'title' => 'Monthly Leadership & Counsel Meetup',
                'category' => 'Leadership',
                'location' => 'Sanctuary Conference Room',
                'description' => 'Dedicated guidance session for community leaders and seekers looking for spiritual mentorship.',
            ],
        ];

        foreach ($guides as $guideIndex => $guide) {
            foreach ($sampleEvents as $eventIndex => $eventData) {
                // Generate upcoming dates in future (1 to 30 days ahead)
                $daysInFuture = ($guideIndex * 2) + ($eventIndex * 4) + 1;
                $eventDate = Carbon::now()->addDays($daysInFuture);

                Event::create([
                    'user_id'         => $guide->id,
                    'title'           => $eventData['title'] . ' (by ' . $guide->name . ')',
                    'category'        => $eventData['category'],
                    'date'            => $eventDate->format('Y-m-d'),
                    'start_time'      => '10:00 AM',
                    'end_time'        => '11:30 AM',
                    'location'        => $eventData['location'],
                    'description'     => $eventData['description'],
                    'visibility'      => 'public',
                    'zoom_session_id' => (string) rand(81000000000, 89999999999),
                    'image'           => null,
                ]);
            }
        }
    }
}
