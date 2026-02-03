<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\ResumeSection;
use Illuminate\Database\Seeder;

class ResumeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ResumeSection::truncate();

        // About Me
        ResumeSection::create([
            'title' => 'About Me',
            'type' => 'about',
            'content' => 'I am a passionate full-stack developer with expertise in Laravel and Vue.js. I love building modern web applications and solving complex problems.',
            'order' => 1,
        ]);

        // Experience
        ResumeSection::create([
            'title' => 'Work Experience',
            'type' => 'experience',
            'data' => [
                [
                    'position' => 'Senior Software Developer',
                    'company' => 'Tech Company Inc.',
                    'period' => '2023 - Present',
                    'description' => 'Lead development of web applications using Laravel and Vue.js.',
                    'technologies' => ['Laravel', 'Vue.js', 'PostgreSQL']
                ],
                [
                    'position' => 'Full Stack Developer',
                    'company' => 'StartUp Co.',
                    'period' => '2021 - 2023',
                    'description' => 'Developed RESTful APIs and modern SPAs.',
                    'technologies' => ['PHP', 'JavaScript', 'MySQL']
                ]
            ],
            'order' => 2,
        ]);

        // Education
        ResumeSection::create([
            'title' => 'Education',
            'type' => 'education',
            'data' => [
                [
                    'degree' => 'Bachelor of Computer Science',
                    'institution' => 'University Name',
                    'period' => '2017 - 2021',
                    'description' => 'Graduated with honors.'
                ]
            ],
            'order' => 3,
        ]);

        // Skills
        ResumeSection::create([
            'title' => 'Skills',
            'type' => 'skills',
            'data' => [
                'languages' => ['PHP', 'JavaScript', 'SQL', 'HTML', 'CSS'],
                'frameworks' => ['Laravel', 'Vue.js', 'Tailwind CSS'],
                'databases' => ['PostgreSQL', 'MySQL'],
                'tools' => ['Git', 'Docker', 'DBeaver', 'VS Code']
            ],
            'order' => 4,
        ]);

        // Projects
        ResumeSection::create([
            'title' => 'Projects',
            'type' => 'projects',
            'data' => [
                [
                    'name' => 'Resume Website',
                    'description' => 'Personal resume website built with Laravel and Vue.js',
                    'technologies' => ['Laravel', 'Vue 3', 'PostgreSQL'],
                    'link' => '#'
                ]
            ],
            'order' => 5,
        ]);
    }
}
