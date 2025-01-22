<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Models\Todoapp;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todoapp::all();
        return view('todos.index', ['todos' => $todos]);
    }

    public function create()
    {
        return view('todos.create');
    }

    public function store(TodoRequest $request)
    {
        Todoapp::create([
            'title' => $request->title,
            'description' => $request->description,
            'is_completed' => 0
        ]);

        session()->flash('alert-success', 'To-do list Created Successfully');
        return redirect()->route('todos.index');
    }

    public function show($id)
    {
        $todo = Todoapp::find($id);
        if (!$todo) {
            session()->flash('error', 'Unable to locate the Todo');
            return redirect()->route('todos.index')->withErrors(['error' => 'Unable to locate the Todo']);
        }
        return view('todos.show', ['todo' => $todo]);
    }

    public function edit($id)
    {
        $todo = Todoapp::find($id);
        if (!$todo) {
            session()->flash('error', 'Unable to locate the Todo');
            return redirect()->route('todos.index')->withErrors(['error' => 'Unable to locate the Todo']);
        }
        return view('todos.edit', ['todo' => $todo]);
    }

    public function update(TodoRequest $request)
    {
        $todo = Todoapp::find($request->todo_id);
        if (!$todo) {
            session()->flash('error', 'Unable to locate the Todo');
            return redirect()->route('todos.index')->withErrors(['error' => 'Unable to locate the Todo']);
        }

        $todo->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_completed' => $request->is_completed
        ]);

        session()->flash('alert-info', 'To-do list Updated Successfully');
        return redirect()->route('todos.index');
    }

    public function destroy(Request $request)
    {
        $todo = Todoapp::find($request->todo_id);
        if (!$todo) {
            session()->flash('error', 'Unable to locate the Todo');
            return redirect()->route('todos.index')->withErrors(['error' => 'Unable to locate the Todo']);
        }

        $todo->delete();
        session()->flash('alert-success', 'To-do list Deleted Successfully');
        return redirect()->route('todos.index');
    }

    public function restore($id)
    {
        $todo = Todoapp::onlyTrashed()->find($id);
        if (!$todo) {
            session()->flash('error', 'Unable to locate the Todo');
            return redirect()->route('todos.restore.view')->withErrors(['error' => 'Unable to locate the Todo']);
        }

        $todo->restore();
        session()->flash('alert-success', 'To-do list Restored Successfully');
        return redirect()->route('todos.index');
    }

    public function restoreView()
    {
        $todos = Todoapp::onlyTrashed()->get();
        return view('todos.restore', ['todos' => $todos]);
    }

    public function delete($id)
    {
        $todo = Todoapp::onlyTrashed()->findOrFail($id);
        $todo->forceDelete();
        return redirect()->route('todos.restore.view')->with('success', 'To-Do permanently deleted!');
    }
}





  