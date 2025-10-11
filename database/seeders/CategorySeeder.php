<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Life Challenges',
                'description' => 'Courses related to personal life challenges',
                'sub_categories' => [
                    ['name' => 'Coping with Loss & Grief', 'description' => 'Handling grief and loss'],
                    ['name' => 'Overcoming Anxiety & Worry', 'description' => 'Managing anxiety and stress'],
                    ['name' => 'Navigating Life Transitions', 'description' => 'Adapting to changes in life'],
                    ['name' => 'Forgiveness & Letting Go', 'description' => 'Learning forgiveness'],
                    ['name' => 'Finding Purpose & Direction', 'description' => 'Discovering life purpose'],
                    ['name' => 'Substance-abuse', 'description' => 'Support for substance issues'],
                ],
            ],
            [
                'name' => 'Relationships',
                'description' => 'Courses to strengthen relationships',
                'sub_categories' => [
                    ['name' => 'Marriage & Commitment', 'description' => 'Building strong marriages'],
                    ['name' => 'Dating & Courtship', 'description' => 'Guidance on dating'],
                    ['name' => 'Parenting & Family Life', 'description' => 'Raising children and family tips'],
                    ['name' => 'Resolving Conflict', 'description' => 'Conflict management skills'],
                    ['name' => 'Building Healthy Friendships', 'description' => 'Developing meaningful friendships'],
                ],
            ],
            [
                'name' => 'Faith & Spiritual Growth',
                'description' => 'Courses for spiritual development',
                'sub_categories' => [
                    ['name' => 'Deepening Your Prayer Life', 'description' => 'Improving prayer practices'],
                    ['name' => 'Understanding Scripture', 'description' => 'Bible study guidance'],
                    ['name' => 'Strengthening Your Faith', 'description' => 'Faith-building courses'],
                    ['name' => 'Overcoming Doubt', 'description' => 'Addressing spiritual doubts'],
                    ['name' => 'Serving in Your Community', 'description' => 'Community service guidance'],
                ],
            ],
            [
                'name' => 'Personal Development',
                'description' => 'Courses to grow personally',
                'sub_categories' => [
                    ['name' => 'Time Management & Balance', 'description' => 'Managing time effectively'],
                    ['name' => 'Decision-Making with Wisdom', 'description' => 'Making wise decisions'],
                    ['name' => 'Resisting Temptation', 'description' => 'Developing self-control'],
                    ['name' => 'Developing Patience', 'description' => 'Learning patience'],
                    ['name' => 'Living with Integrity', 'description' => 'Maintaining ethical behavior'],
                ],
            ],
            [
                'name' => 'Special Circumstances',
                'description' => 'Courses for unique life situations',
                'sub_categories' => [
                    ['name' => 'Career & Work Guidance', 'description' => 'Career advice and guidance'],
                    ['name' => 'Financial Stewardship', 'description' => 'Managing finances responsibly'],
                    ['name' => 'Health & Healing Support', 'description' => 'Wellness and healing guidance'],
                    ['name' => 'Mission & Ministry Calling', 'description' => 'Spiritual vocation guidance'],
                    ['name' => 'Coping During Crisis', 'description' => 'Support during challenging times'],
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $category = Category::create([
                'name' => $catData['name'],
                'description' => $catData['description'],
                'status' => 'active',
            ]);

            foreach ($catData['sub_categories'] as $subData) {
                $category->subCategories()->create([
                    'name' => $subData['name'],
                    'description' => $subData['description'],
                    'status' => 'active',
                ]);
            }
        }
    }
}
