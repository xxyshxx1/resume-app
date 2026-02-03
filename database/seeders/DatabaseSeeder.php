<?php

namespace Database\Seeders;

// use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    // use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('resume_sections')->truncate();

        DB::table('resume_sections')->insert([
            [
                'title' => 'About Me',
                'type' => 'about',
                'content' => 'I am a passionate full-stack developer with expertise in Laravel and Vue.js. I love building modern web applications.',
                'data' => null,
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Work Experience',
                'type' => 'experience',
                'content' => null,
                'data' => json_encode([
                    [
                        'position' => 'Senior Developer',
                        'company' => 'Tech Company',
                        'period' => '2023 - Present',
                        'description' => 'Lead development of web applications.',
                        'technologies' => ['Laravel', 'Vue.js', 'PostgreSQL']
                    ]
                ]),
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Skills',
                'type' => 'skills',
                'content' => null,
                'data' => json_encode([
                    'languages' => ['PHP', 'JavaScript', 'SQL'],
                    'frameworks' => ['Laravel', 'Vue.js'],
                    'tools' => ['Git', 'Docker', 'VS Code']
                ]),
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
