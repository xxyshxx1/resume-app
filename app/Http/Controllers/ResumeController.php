<?php

namespace App\Http\Controllers;

use App\Models\ResumeSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResumeController extends Controller
{
    /**
     * Get all resume sections for the authenticated user
     */
    public function index()
    {
        $sections = ResumeSection::forUser(Auth::id())
            ->active()
            ->ordered()
            ->get();

        return response()->json($sections);
    }

    /**
     * Get a public resume for a specific user (by user ID)
     */
    public function show($userId)
    {
        $sections = ResumeSection::forUser($userId)
            ->active()
            ->ordered()
            ->get();

        return response()->json($sections);
    }

    /**
     * Store a new resume section
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:about,experience,education,skills,projects',
            'content' => 'nullable|string',
            'data' => 'nullable|array',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $section = ResumeSection::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'type' => $validated['type'],
            'content' => $validated['content'] ?? null,
            'data' => $validated['data'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json($section, 201);
    }

    /**
     * Update a resume section
     */
    public function update(Request $request, ResumeSection $section)
    {
        // Ensure user owns this section
        if ($section->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|in:about,experience,education,skills,projects',
            'content' => 'nullable|string',
            'data' => 'nullable|array',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $section->update($validated);

        return response()->json($section);
    }

    /**
     * Delete a resume section
     */
    public function destroy(ResumeSection $section)
    {
        // Ensure user owns this section
        if ($section->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $section->delete();

        return response()->json(['message' => 'Section deleted successfully']);
    }

    /**
     * Initialize default sections for a new user
     */
    public function initializeDefaults()
    {
        $userId = Auth::id();

        // Check if user already has sections
        if (ResumeSection::forUser($userId)->exists()) {
            return response()->json(['message' => 'Sections already exist'], 400);
        }

        $defaults = [
            [
                'title' => 'About Me',
                'type' => 'about',
                'content' => 'Write something about yourself here...',
                'order' => 1,
            ],
            [
                'title' => 'Work Experience',
                'type' => 'experience',
                'data' => [],
                'order' => 2,
            ],
            [
                'title' => 'Education',
                'type' => 'education',
                'data' => [],
                'order' => 3,
            ],
            [
                'title' => 'Skills',
                'type' => 'skills',
                'data' => [
                    'frontend' => [],
                    'backend' => [],
                    'tools' => [],
                ],
                'order' => 4,
            ],
            [
                'title' => 'Projects',
                'type' => 'projects',
                'data' => [],
                'order' => 5,
            ],
        ];

        foreach ($defaults as $section) {
            ResumeSection::create([
                'user_id' => $userId,
                ...$section,
                'is_active' => true,
            ]);
        }

        return response()->json(['message' => 'Default sections created']);
    }
}
