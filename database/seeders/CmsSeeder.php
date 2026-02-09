<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;
use App\Models\Achievement;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        // Default Site Settings
        $settings = [
            'hero_title' => 'Master Your Network, Shape Your Future.',
            'hero_subtitle' => "Welcome to the official Cisco NetRiders assessment portal. Step into the world's most prestigious IT competition and prove your skills on a global stage.",
            'about_title' => 'About Cisco NetRiders',
            'about_content' => "NetRiders is an interactive networking competition that provides students with the opportunity to test their networking/IT skills, gain visibility among recruiters, and win prizes.",
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Sample Achievement
        Achievement::updateOrCreate(
            ['title' => 'Cisco NetRiders 2026 Season Kickoff'],
            [
                'content' => 'We are excited to announce the start of the 2026 competition season. Join thousands of students across the globe in the ultimate networking challenge.',
                'date' => now(),
                'is_active' => true,
            ]
        );
    }
}
