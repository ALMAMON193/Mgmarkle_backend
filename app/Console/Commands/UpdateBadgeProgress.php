<?php

namespace App\Console\Commands;

use App\Models\BadgeCategory;
use App\Models\BadgeComplete;
use App\Models\User;
use Illuminate\Console\Command;

class UpdateBadgeProgress extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'badges:update-progress';

    /**
     * The console command description.
     */
    protected $description = 'Update user badge progress for all badge categories';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Updating badge progress for all users...');

        $users = User::with([
            'stackCounts',
            'photos',
            'journeys',
            'voiceNotes',
            'journalEntries',
            'badgeCompletes',
        ])->get();

        $badges = BadgeCategory::all();

        foreach ($users as $user) {
            foreach ($badges as $badge) {
                $progress = $this->calculateProgress($user, $badge);

                BadgeComplete::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'badge_category_id' => $badge->id,
                    ],
                    [
                        'progress' => $progress,
                        'is_complete' => $progress >= 100,
                    ]
                );
            }
        }

        $this->info('Badge progress updated successfully!');
    }

    /**
     * Calculate consecutive streak for a user based on stack_counts.
     */
    protected function calculateStreak(User $user, int $requiredDays): int
    {
        $dates = $user->stackCounts
            ->pluck('date')
            ->map(fn ($d) => $d->toDateString())
            ->toArray();

        $streak = 0;

        for ($i = 0; $i < $requiredDays; $i++) {
            $checkDate = now()->subDays($i)->toDateString();
            if (in_array($checkDate, $dates)) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }
}
