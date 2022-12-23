<div>
    <ul>
        @foreach ($todos as $todo)
        <li>
            <a href="{{ route('livewire-tutorial.todos.update',['todo'=>$todo->id]) }}">
                タイトル:{{ $todo->title }}
            </a>
            <span>/</span>
            <a>コンテンツ:{{ $todo->content }}</a>
            <button wire:click="delete({{ $todo->id }})">削除</button>
        </li>
        @endforeach
    </ul>
    <a href="{{ route('livewire-tutorial.todos.create') }}">作成</a>
</div>