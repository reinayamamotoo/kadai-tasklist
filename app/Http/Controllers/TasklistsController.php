<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\TasklitsController;
use App\Tasklist;
use App\User;

class TasklistsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasklists = $user->tasklists()->orderBy('created_at', 'desc')->paginate(10);

            $data = [
                'user' => $user,
                'tasklists' => $tasklists,
            ];
            $data += $this->counts($user);
            return view('tasklists.index', $data);
        }else {
            return view('welcome');
        }
    }
    
    public function show($id)
    {
 
        return view('tasklists.show', [
            'tasklist' => Tasklist::find($id)
        ]);


    }
    
    public function edit($id)
    {
        $tasklist = Tasklist::find($id);

        return view('tasklists.edit', [
            'tasklist' => $tasklist,
        ]);
    }
    
    public function create()
    {
        $tasklist = new Tasklist;

        return view('tasklists.create', [
            'tasklist' => $tasklist,
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $tasklist = Tasklist::find($id);
        $tasklist->content = $request->content;
        $tasklist->status = $request->status;
        $tasklist->save();

        return redirect('/');
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:191',
            'status' => 'required|max:191',
        ]);

        $request->user()->tasklists()->create([
            'content' => $request->content,
            'status' => $request->status,
        ]);

        return redirect('/');
    }
    
    public function destroy($id)
    {
        $tasklist = \App\Tasklist::find($id);

        if (\Auth::user()->id === $tasklist->user_id) {
            $tasklist->delete();
        }

        return redirect('/');
    }
    
    
}
