<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {

        $projects = Project::where([
            ['name', '!=', null],
            [function ($query) use ($request) {
            if (($term = $request->term)) {
                $query->orWhere('name', 'LIKE', '%'. $term. '%')->get();
            }
            }]
        ])->orderBy("id", "desc")
          ->paginate(5);
    //  $projects = Project::latest()->paginate(5);

        return view('project.index',compact('projects'))
            ->with('i', (request()->input('page', 1) - 1) * 5);

        }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::all();
        return view('project.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $request->validate([
             'name' => 'required|unique:projects,name',
             'detail' => 'required'
      ]);

      Project::create($request->all());
      return redirect()->route('project.index')
                       ->with('success', 'Project created successfully !');


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $project = Project::find($project->id);
        return view('project.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|unique:projects,name',
            'detail' => 'required'
        ]);

       $project->update($request->all());
        return redirect()->route('project.index')
                         ->with('success', 'Project updated successfully !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('project.index')
                         ->with('success', 'Project deleted successfully !');
    }
}
