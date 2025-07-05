<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return response()->json($projects);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:ongoing,completed,upcoming',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'budget' => 'nullable|string|max:255',
            'partners' => 'nullable|json',
            'progress' => 'required|integer|min:0|max:100',
            'impact' => 'nullable|json',
            'objectives' => 'nullable|json'
        ]);

        $project = new Project();
        $project->title = $request->title;
        $project->description = $request->description;
        $project->category = $request->category;
        $project->location = $request->location;
        $project->duration = $request->duration;
        $project->budget = $request->budget;
        $project->partners = $request->partners;
        $project->progress = $request->progress;
        $project->impact = $request->impact;
        $project->objectives = $request->objectives;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('projects', 'public');
            $project->image = $imagePath;
        }

        $project->save();
        return response()->json($project, 201);
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);
        return response()->json($project);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:ongoing,completed,upcoming',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'location' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'budget' => 'nullable|string|max:255',
            'partners' => 'nullable|json',
            'progress' => 'required|integer|min:0|max:100',
            'impact' => 'nullable|json',
            'objectives' => 'nullable|json'
        ]);

        $project = Project::findOrFail($id);
        $project->title = $request->title;
        $project->description = $request->description;
        $project->category = $request->category;
        $project->location = $request->location;
        $project->duration = $request->duration;
        $project->budget = $request->budget;
        $project->partners = $request->partners;
        $project->progress = $request->progress;
        $project->impact = $request->impact;
        $project->objectives = $request->objectives;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $imagePath = $request->file('image')->store('projects', 'public');
            $project->image = $imagePath;
        }

        $project->save();
        return response()->json($project);
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        
        // Delete the project image if it exists
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        
        $project->delete();
        return response()->json(null, 204);
    }
}
