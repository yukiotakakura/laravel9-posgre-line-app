<div>
    <form wire:submit.prevent="update">
        <div>
            <p>タイトル</p>
            <input type="text" wire:model="todo.title">
        </div>

        <div>
            <p>内容</p>
            <textarea wire:model="todo.content">
            </textarea>
        </div>

        <button type="submit">更新</button>
    </form>
</div>