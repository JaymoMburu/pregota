<?php

namespace Database\Seeders;

use App\Models\FeedbackTag;
use Illuminate\Database\Seeder;

class FeedbackTagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            // General
            ['tag' => 'Friendly',        'emoji' => '😊', 'category' => 'general',    'sort_order' => 1],
            ['tag' => 'Attentive',       'emoji' => '👀', 'category' => 'general',    'sort_order' => 2],
            ['tag' => 'Fast Service',    'emoji' => '⚡', 'category' => 'general',    'sort_order' => 3],
            ['tag' => 'Patient',         'emoji' => '🤝', 'category' => 'general',    'sort_order' => 4],
            ['tag' => 'Professional',    'emoji' => '💼', 'category' => 'general',    'sort_order' => 5],
            ['tag' => 'Went Extra Mile', 'emoji' => '🌟', 'category' => 'general',    'sort_order' => 6],

            // Restaurant
            ['tag' => 'Great Food Tips', 'emoji' => '🍽️', 'category' => 'restaurant', 'sort_order' => 7],
            ['tag' => 'Knew the Menu',   'emoji' => '📋', 'category' => 'restaurant', 'sort_order' => 8],
            ['tag' => 'Perfect Timing',  'emoji' => '⏱️', 'category' => 'restaurant', 'sort_order' => 9],
            ['tag' => 'Clean Table',     'emoji' => '✨', 'category' => 'restaurant', 'sort_order' => 10],

            // Salon
            ['tag' => 'Skilled',         'emoji' => '💅', 'category' => 'salon',      'sort_order' => 7],
            ['tag' => 'Great Advice',    'emoji' => '💬', 'category' => 'salon',      'sort_order' => 8],
            ['tag' => 'Gentle',          'emoji' => '🌸', 'category' => 'salon',      'sort_order' => 9],
            ['tag' => 'On Time',         'emoji' => '⏰', 'category' => 'salon',      'sort_order' => 10],

            // Hotel
            ['tag' => 'Welcoming',       'emoji' => '🏨', 'category' => 'hotel',      'sort_order' => 7],
            ['tag' => 'Helpful',         'emoji' => '🙋', 'category' => 'hotel',      'sort_order' => 8],
            ['tag' => 'Discreet',        'emoji' => '🤫', 'category' => 'hotel',      'sort_order' => 9],
            ['tag' => 'Proactive',       'emoji' => '🎯', 'category' => 'hotel',      'sort_order' => 10],

            // Delivery
            ['tag' => 'Fast Delivery',   'emoji' => '🚀', 'category' => 'delivery',   'sort_order' => 7],
            ['tag' => 'Careful',         'emoji' => '📦', 'category' => 'delivery',   'sort_order' => 8],
            ['tag' => 'Communicative',   'emoji' => '📱', 'category' => 'delivery',   'sort_order' => 9],
            ['tag' => 'Polite',          'emoji' => '👋', 'category' => 'delivery',   'sort_order' => 10],
        ];

        foreach ($tags as $tag) {
            FeedbackTag::firstOrCreate(['tag' => $tag['tag'], 'category' => $tag['category']], $tag);
        }
    }
}
