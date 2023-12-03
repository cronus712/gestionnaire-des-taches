<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\TaskAssigned;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         //  $tasks = Task::latest()->paginate(5);
         $users = User::all();
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


        return view('task.index',compact('tasks', 'users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(User $user, Project $project)
    {
        $users = User::all();
        $projects = Project::all();

        return view('task.create', compact('users','projects'));
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
            'project_id' => 'required',
            'priority' => 'required'
        ]);

        Task::create($request->all());
        User::find(Auth ::user()->id)->notify(new TaskAssigned($task->name));
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
        'name' => 'required',
        'detail' => 'required',
        'user_id' => 'required',
        'project_id' =>'required',
        'priority' => 'required'

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

    public function markAsRead(){
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }


    // public function calendarIndex(Request $request) {

    //         if ($request->ajax()) {
    //             $data = Task::whereDate('start', '>=', $request->start)
    //                 ->whereDate('end',   '<=', $request->end)
    //                 ->get(['id', 'title', 'start', 'end']);

    //             return response()->json($data);
    //         }

    //         return view('task.calendar');

    // }

    // public function calendarStore(Request $request)
    // {
    //     switch ($request->type) {
    //         case 'add':
    //             $event = Task::create([
    //                 'title' => $request->title,
    //                 'start' => $request->start,
    //                 'end' => $request->end,
    //             ]);
    //             return response()->json($event);
    //             break;

    //         case 'update':
    //             $event = Task::find($request->id)->update([
    //                 'title' => $request->title,
    //                 'start' => $request->start,
    //                 'end' => $request->end,
    //             ]);
    //             return response()->json($event);
    //             break;

    //         case 'delete':
    //             $event = Task::find($request->id)->delete();
    //             return response()->json($event);
    //             break;

    //         default:
    //             break;
    //     }
    // }
}
