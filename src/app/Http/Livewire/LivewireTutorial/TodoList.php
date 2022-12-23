<?php

namespace App\Http\Livewire\LivewireTutorial;

use App\Models\LivewireTutorialTodo;
use Livewire\Component;

class TodoList extends Component
{
    public $todos;

    public function mount()
    {
        $this->todos = LivewireTutorialTodo::all();
    }

    public function render()
    {
        return view('livewire.livewire-tutorial.todo-list')
            ->extends('livewire.livewire-tutorial.layouts.app');
    }

    public function delete($id)     //追記部分
    {
        LivewireTutorialTodo::find($id)->delete();
        $this->todos = LivewireTutorialTodo::all();
    }
}
