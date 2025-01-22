<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Protect To-Do Routes with Auth Middleware
Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // To-Do Routes
    Route::get('todos/index', [TodoController::class, 'index'])->name('todos.index');
    Route::get('/todos/create', [TodoController::class, 'create'])->name('todos.create');
    Route::post('/todos/store', [TodoController::class, 'store'])->name('todos.store');
    Route::get('/todos/show/{id}', [TodoController::class, 'show'])->name('todos.show');
    Route::get('/todos/{id}/edit', [TodoController::class, 'edit'])->name('todos.edit');
    Route::put('/todos/update', [TodoController::class, 'update'])->name('todos.update');
    Route::delete('/todos/destroy', [TodoController::class, 'destroy'])->name('todos.destroy');
     // Route for showing deleted to-dos
     Route::get('todos/restore', [TodoController::class, 'restoreView'])->name('todos.restore.view');

     // Route for restoring a deleted Todo
     Route::post('todos/{id}/restore', [TodoController::class, 'restore'])->name('todos.restore'); 

     Route::delete('/todos/{id}/delete', [TodoController::class, 'delete'])->name('todos.delete');

});
