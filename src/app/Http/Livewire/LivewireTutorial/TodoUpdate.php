<?php

namespace App\Http\Livewire\LivewireTutorial;

use App\Models\LivewireTutorialTodo;
use Livewire\Component;

class TodoUpdate extends Component
{
    public LivewireTutorialTodo $todo;

    protected array $rules = [
        'todo.title' => 'required|string|max:255',
        'todo.content' => 'require|string|max:255',
    ];

    // protected array $rules = [
    //     'title' => 'required|string|max:255',
    //     'content' => 'required|string|max:255',
    // ];

    public function render()
    {
        return view('livewire.livewire-tutorial.todo-update');
    }

    public function update()
    {
        //$this->validate();
        $this->todo->update();
    }
}
