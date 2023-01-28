<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Line\TestController;
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

// Line
Route::prefix('login/{provider}')->where(['provider' => '(line)'])->group(function () {
    Route::get('/', [LoginController::class, 'redirectToProvider'])->name('social_login.redirect');
    Route::get('/callback', [LoginController::class, 'handleProviderCallback'])->name('social_login.callback');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    // Line
    Route::get('/line/test', [TestController::class, 'index'])->name('line.test.index');
    // livewireチュートリアル
    Route::get('livewire-tutorial/todos/create', TodoCreate::class)->name('livewire-tutorial.todos.create');
    Route::get('livewire-tutorial/todos', TodoList::class)->name('livewire-tutorial.todos');
    Route::get('livewire-tutorial/todos/{todo}', TodoUpdate::class)->name('livewire-tutorial.todos.update');
});
