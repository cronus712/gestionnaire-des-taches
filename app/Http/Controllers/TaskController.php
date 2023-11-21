<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::all();
        //  $tasks = Task::latest()->paginate(5);
         $projects = Project::all();
         $tasks = Task::where([
            ['name', '!=', null],
            [function ($query) use ($request) {
            if (($term = $request->term)) {
                $query->orWhere('name', 'LIKE', '%'. $term. '%')->get();
            }
            }]
        ])->orderBy("id", "desc")
          ->paginate(5);


        return view('tasks.index',compact('tasks', 'users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(User $user, Project $project)
    {
        $user = User::all();
        $project = Project::all();

        return view('task.create', compact('user','project'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required|unique:tasks,name',
            'detail' => 'required',
            'user_id' => 'required',
            'project_id' => 'required'
        ]);

        Task::create($request->all());

        return redirect()->route('task.index')
                         ->with('Success', 'Task created successfully !');

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
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $user = User::all();
        $project = Project::all();

        return view('task.edit',compact('task', 'user', 'project'));    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project, User $user, Task $task)
    {
       $request->validate([
        'name' => 'required|unique:tasks,name',
        'detail' => 'required',
        'user_id' => 'required',
        'project_id' =>'required'
       ]);

       $task->update($request->all());

       return redirect()->route('task.index')
                       ->with('Success', 'Task updated successfully !');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('task.index')
                         ->with('Success', 'Task deleted successfully !');
    }
}
