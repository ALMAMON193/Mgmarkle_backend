<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgeCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            // Daily Streak Badges
            [
                'badge_code' => 'STREAK_01',
                'name' => 'Day One',
                // 'icon' => 'streak_day_one.png',
                'threshold' => '1 day',
                'unlock_toast' => 'Your very first journal entry. The adventure begins!',
                'reward_points' => 25,
                'status' => 'active',
            ],
            [
                'badge_code' => 'STREAK_03',
                'name' => 'Spark Starter',
                // 'icon' => 'streak_spark.png',
                'threshold' => '3 days',

                'unlock_toast' => " 3-day streak! You're warming up.",
                'reward_points' => 50,
                'status' => 'active',
            ],
            [
                'badge_code' => 'STREAK_05',
                'name' => 'High-Five',
                // 'icon' => 'streak_high_five.png',
                'threshold' => '5 days',

                'unlock_toast' => '5 days in a row. Momentum looks good.',
                'reward_points' => 75,
                'status' => 'active',
            ],
            [
                'badge_code' => 'STREAK_07',
                'name' => 'One-Week Flame',
                // 'icon' => 'streak_flame.png',
                'threshold' => '7 days',

                'unlock_toast' => 'Perfect week! Keep it rolling.',
                'reward_points' => 100,
                'status' => 'active',
            ],
            [
                'badge_code' => 'STREAK_10',
                'name' => 'Double Digits',
                // 'icon' => 'streak_double_digits.png',
                'threshold' => '10 days',

                'unlock_toast' => '10 days—habit unlocked.',
                'reward_points' => 120,
                'status' => 'active',
            ],
            [
                'badge_code' => 'STREAK_14',
                'name' => 'Two-Week Groove',
                // 'icon' => 'streak_groove.png',
                'threshold' => '14 days',

                'unlock_toast' => 'Two weeks of memories, captured.',
                'reward_points' => 150,
                'status' => 'active',
            ],
            [
                'badge_code' => 'STREAK_21',
                'name' => 'Habit Builder',
                // 'icon' => 'streak_habit.png',
                'threshold' => '21 days',

                'unlock_toast' => " 21 days. That's a real habit.",
                'reward_points' => 200,
                'status' => 'active',
            ],
            [
                'badge_code' => 'STREAK_30',
                'name' => 'Monthly Streak',
                // 'icon' => 'streak_monthly.png',
                'threshold' => '30 days',

                'unlock_toast' => '30 days straight—epic consistency.',
                'reward_points' => 250,
                'status' => 'active',
            ],
            [
                'badge_code' => 'STREAK_50',
                'name' => 'Trailblazer',
                // 'icon' => 'streak_trailblazer.png',
                'threshold' => '50 days',

                'unlock_toast' => " 50 days! You're blazing trails.",
                'reward_points' => 400,
                'status' => 'active',
            ],
            [
                'badge_code' => 'STREAK_75',
                'name' => 'Voyager',
                // 'icon' => 'streak_voyager.png',
                'threshold' => '75 days',

                'unlock_toast' => '75 days—unstoppable.',
                'reward_points' => 600,
                'status' => 'active',
            ],
            [
                'badge_code' => 'STREAK_100',
                'name' => 'Century Keeper',
                // 'icon' => 'streak_century.png',
                'threshold' => '100 days',

                'unlock_toast' => '100 days in a row. Legendary.',
                'reward_points' => 1000,
                'status' => 'active',
            ],

            // Photographer Badges
            [
                'badge_code' => 'PHOTO_01',
                'name' => 'Shutterbug',
                // 'icon' => 'photo_shutterbug.png',
                'unlock_criteria' => 'Upload d 1 photo',
                'unlock_toast' => 'First snap saved! Your journal just got prettier.',
                'reward_points' => 5,
                'status' => 'active',
            ],
            [
                'badge_code' => 'PHOTO_10',
                'name' => 'Memory Collector',
                // 'icon' => 'photo_collector.png',

                'unlock_criteria' => 'Upload d 10 photos',
                'unlock_toast' => '10 moments captured — keep them coming.',
                'reward_points' => 1,
                'status' => 'active',
            ],
            [
                'badge_code' => 'PHOTO_25',
                'name' => 'Photo Explorer',
                // 'icon' => 'photo_explorer.png',

                'unlock_criteria' => 'Upload d 25 photos',
                'unlock_toast' => " Your trip's turning into a photo album.",
                'reward_points' => 2,
                'status' => 'active',
            ],
            [
                'badge_code' => 'PHOTO_50',
                'name' => 'Photo Master',
                // 'icon' => 'photo_master.png',

                'unlock_criteria' => 'Upload d 50 photos',
                'unlock_toast' => " 50 photos! You're basically a travel photographer now.",
                'reward_points' => 4,
                'status' => 'active',
            ],
            [
                'badge_code' => 'PHOTO_100',
                'name' => 'Pixel Pioneer',
                // 'icon' => 'photo_pioneer.png',

                'unlock_criteria' => 'Upload d 100 photos',
                'unlock_toast' => '100 memories locked in — epic!',
                'reward_points' => 8,
                'status' => 'active',
            ],

            // Explorer Badges (Journey)
            [
                'badge_code' => 'JOURNEY_01',
                'name' => 'The Explorer',
                // 'icon' => 'journey_explorer.png',

                'unlock_criteria' => 'Create your first journey',
                'unlock_toast' => 'Your first journey is live — adventure awaits!',
                'reward_points' => 5,
                'status' => 'active',
            ],
            [
                'badge_code' => 'JOURNEY_05',
                'name' => 'Globetrotter',
                // 'icon' => 'journey_globetrotter.png',

                'unlock_criteria' => 'Create 5 journeys',
                'unlock_toast' => '5 journeys started — the world is your playground.',
                'reward_points' => 1,
                'status' => 'active',
            ],
            [
                'badge_code' => 'JOURNEY_10',
                'name' => 'Nomad',
                // 'icon' => 'journey_nomad.png',

                'unlock_criteria' => 'Create 10 journeys',
                'unlock_toast' => " 10 journeys! You're living the traveler's life.",
                'reward_points' => 4,
                'status' => 'active',
            ],
            [
                'badge_code' => 'JOURNEY_20',
                'name' => 'World Wanderer',
                // 'icon' => 'journey_wanderer.png',

                'unlock_criteria' => 'Create 20 journeys',
                'unlock_toast' => '20 journeys — an epic global adventure.',
                'reward_points' => 8,
                'status' => 'active',
            ],
            [
                'badge_code' => 'JOURNEY_COMP_01',
                'name' => 'Finisher',
                // 'icon' => 'journey_finisher.png',

                'unlock_criteria' => 'Complete te your first journey',
                'unlock_toast' => 'First journey completed — memories locked!',
                'reward_points' => 1,
                'status' => 'active',
            ],
            [
                'badge_code' => 'JOURNEY_COMP_10',
                'name' => 'Epic Voyager',
                // 'icon' => 'journey_voyager.png',

                'unlock_criteria' => 'Complete te 10 journeys',
                'unlock_toast' => " 10 trips completed — you've seen the world.",
                'reward_points' => 7,
                'status' => 'active',
            ],

            // Adventure Badges (Voice)
            [
                'badge_code' => 'VOICE_01',
                'name' => 'Sound Collector',
                // 'icon' => 'voice_collector.png',

                'unlock_criteria' => 'Record 1 voice note',
                'unlock_toast' => 'Your first voice note — travel vibes saved.',
                'reward_points' => 5,
                'status' => 'active',
            ],
            [
                'badge_code' => 'VOICE_10',
                'name' => 'Audio Adventurer',
                // 'icon' => 'voice_adventurer.png',

                'unlock_criteria' => 'Record 10 voice notes',
                'unlock_toast' => " 10 voice notes! You're capturing the trip in full stereo.",
                'reward_points' => 1,
                'status' => 'active',
            ],
            [
                'badge_code' => 'VOICE_25',
                'name' => " Storyteller's Mic",
                // 'icon' => 'voice_storyteller.png',

                'unlock_criteria' => 'Record 25 voice notes',
                'unlock_toast' => 'Your voice is shaping an amazing story.',
                'reward_points' => 4,
                'status' => 'active',
            ],

            // Storyteller Badges (Writing)
            [
                'badge_code' => 'WRITE_01',
                'name' => 'Memory Maker',
                // 'icon' => 'writing_maker.png',

                'unlock_criteria' => 'Write your first entry',
                'unlock_toast' => " First journal entry done — you're on your way!",
                'reward_points' => 5,
                'status' => 'active',
            ],
            [
                'badge_code' => 'WRITE_10',
                'name' => 'The Scribbler',
                // 'icon' => 'writing_scribbler.png',

                'unlock_criteria' => 'Write 10 entries',
                'unlock_toast' => '10 memories locked in — keep it up!',
                'reward_points' => 1,
                'status' => 'active',
            ],
            [
                'badge_code' => 'WRITE_25',
                'name' => 'Storyteller',
                // 'icon' => 'writing_storyteller.png',

                'unlock_criteria' => 'Write 25 entries',
                'unlock_toast' => " 25 entries! You've got tales for days",
                'reward_points' => 2,
                'status' => 'active',
            ],
            [
                'badge_code' => 'WRITE_50',
                'name' => 'Author Abroad',
                // 'icon' => 'writing_author.png',

                'unlock_criteria' => 'Write 50 entries',
                'unlock_toast' => " 50 stories saved — you're writing your own book.",
                'reward_points' => 4,
                'status' => 'active',
            ],
            [
                'badge_code' => 'WRITE_100',
                'name' => 'The Journey Jotter',
                // 'icon' => 'writing_jotter.png',

                'unlock_criteria' => 'Write 100 entries',
                'unlock_toast' => "100 entries! That's a travel epic for the ages.",
                'reward_points' => 1,
                'status' => 'active',
            ],

            // Special / Meta Badges
            [
                'badge_code' => 'META_01',
                'name' => 'Achievement Unlocked',
                // 'icon' => 'meta_achievement.png',

                'unlock_criteria' => 'Earn your first badge',
                'unlock_toast' => "Your first badge! Feels good, doesn't it?",
                'reward_points' => 50,
                'status' => 'active',
            ],
            [
                'badge_code' => 'META_10',
                'name' => 'Badge Collector',
                // 'icon' => 'meta_collector.png',

                'unlock_criteria' => 'Unlock 10 badges total',
                'unlock_toast' => "10 badges! You're officially addicted.",
                'reward_points' => 25,
                'status' => 'active',
            ],
            [
                'badge_code' => 'META_20',
                'name' => 'Super Jotter',
                // 'icon' => 'meta_super.png',

                'unlock_criteria' => 'Unlock 20 badges total',
                'unlock_toast' => "20 badges — you've beaten the system.",
                'reward_points' => 60,
                'status' => 'active',
            ],
        ];
        DB::table('badge_categories')->insert($badges);
    }
}
