<div>
    <form wire:submit.prevent="save">
        <div>
            <p>タイトル</p>
            <input type="text" wire:model="title">
        </div>

        <div>
            <p>内容</p>
            <textarea wire:model="content"></textarea>
        </div>

        <button type="submit">保存</button>
    </form>
</div>