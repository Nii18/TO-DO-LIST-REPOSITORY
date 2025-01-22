@extends('layouts.app')

@section('styles')
<style>
    #outer {
        width: auto;
        text-align: center;
    }
    .inner {
        display: inline-block;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Deleted To-Do Lists') }}</div>
               
                <div class="card-body">
                    @if (Session::has('alert-success'))   
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('alert-success') }}
                        </div>   
                    @endif

                    @if (count($todos) > 0)
                        <table class="table">
                            <a href="{{route('todos.index')}}" class="btn btn-sm btn-primary">Go Back</a>
                            <thead>
                                <tr>
                                    <th scope="col">Title</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Deleted At</th>
                                    <th scope="col">Action</th> 
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($todos as $todo)
                                    <tr>
                                        <td>{{ $todo->title }}</td>
                                        <td>{{ $todo->description }}</td>
                                        <td>{{ $todo->deleted_at->format('Y-m-d H:i:s') }}</td>
                                        <td id="outer">
                                            <form method="post" action="{{ route('todos.restore', $todo->id) }}" class="inner">
                                                @csrf
                                                <input type="submit" class="btn btn-sm btn-primary" value="Restore">
                                            </form>

                                              <!-- Delete Button -->
                                            <form action="{{ route('todos.delete', $todo->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure you want to delete this item permanently?');">
                                                    Permanently Delete
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> 
                    @else 
                        <h4>No deleted To-do lists found</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
