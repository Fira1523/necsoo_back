<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('portfolios')->insert([
            [
                'title' => 'Promoting Enabling Environment for CSOs',
                'category' => 'ongoing',
                'image' => '/images/portfolio/project1.jpg',
                'description' => 'A European Union-funded project aimed at advancing civic space and enhancing development partnership opportunities in the Oromia Regional State.',
                'location' => 'Oromia Region',
                'duration' => '2023-2025',
                'budget' => '€500,000',
                'partners' => json_encode(['European Union', 'Welt Hunger Hilfe', 'CoSAP', 'DEC']),
                'progress' => 45,
                'impact' => json_encode([
                    'Enhanced CSO operational environment',
                    'Strengthened partnerships',
                    'Improved advocacy capabilities',
                ]),
                'objectives' => json_encode([
                    'Advance civic space for CSOs',
                    'Promote development partnerships',
                    'Build organizational capacity',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more projects as needed
        ]);
    }
}

