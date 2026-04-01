<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $query = Achievement::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $achievements = $query->paginate(10)->withQueryString();
        return view('admin.achievements.index', compact('achievements'));
    }

    public function create()
    {
        return view('admin.achievements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'date' => 'nullable|date',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('achievements', 'public');
        }

        Achievement::create($data);

        return redirect()->route('admin.achievements.index')->with('success', 'Achievement added successfully!');
    }

    public function edit(Achievement $achievement)
    {
        return view('admin.achievements.edit', compact('achievement'));
    }

    public function update(Request $request, Achievement $achievement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'date' => 'nullable|date',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            if ($achievement->image_path) {
                Storage::disk('public')->delete($achievement->image_path);
            }
            $data['image_path'] = $request->file('image')->store('achievements', 'public');
        }

        $achievement->update($data);

        return redirect()->route('admin.achievements.index')->with('success', 'Achievement updated successfully!');
    }

    public function destroy(Achievement $achievement)
    {
        if ($achievement->image_path) {
            Storage::disk('public')->delete($achievement->image_path);
        }
        $achievement->delete();

        return redirect()->route('admin.achievements.index')->with('success', 'Achievement deleted successfully!');
    }
}
