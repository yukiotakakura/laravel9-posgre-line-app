<?php

namespace App\Http\Livewire\LivewireTutorial;

use App\Models\LivewireTutorialTodo;
use Livewire\Component;

class TodoCreate extends Component
{
    public string $title = "";
    public string $content = "";

    protected array $rules = [
        'title' => 'required|string|max:255',
        'content' => 'required|string|max:255',
    ];

    public function render()
    {
        return view('livewire.livewire-tutorial.todo-create')
            ->extends('livewire.livewire-tutorial.layouts.app');
    }

    public function save()
    {
        $this->validate();

        LivewireTutorialTodo::create([
            "title" => $this->title,
            "content" => $this->content
        ]);

        $this->reset();
    }
}
