<?php

use App\Http\Livewire\LivewireTutorial\TodoCreate;
use App\Http\Livewire\LivewireTutorial\TodoList;
use App\Http\Livewire\LivewireTutorial\TodoUpdate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('livewire-tutorial/todos/create', TodoCreate::class)->name('livewire-tutorial.todos.create');
    Route::get('livewire-tutorial/todos', TodoList::class)->name('livewire-tutorial.todos');
    Route::get('todos/{todo}', TodoUpdate::class)->name('livewire-tutorial.todos.update');
});
