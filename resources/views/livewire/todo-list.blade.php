<div>
    <h5 class="fw-bold">To-Do List</h5>

    <!-- To-Do List Form -->
    <form wire:submit.prevent="addTodo">
        <div class="input-group mb-3">
            <input wire:model="newTodo" type="text" class="form-control" placeholder="Enter task..." required>
            <button type="submit" class="btn" style="background-color:#FBBB4C;">Add</button>
        </div>
    </form>

    <!-- To-Do List Display -->
    <ul class="list-group">
        @foreach($todos as $todo)
            <li class="list-group-item d-flex justify-content-between">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" wire:click="toggleCompletion({{ $todo->id }})" {{ $todo->completed ? 'checked' : '' }}>
                    <label class="form-check-label {{ $todo->completed ? 'text-decoration-line-through' : '' }}">
                        {{ $todo->task }}
                    </label>
                </div>
                <!-- Delete Button -->
                <button type="button" class="btn btn-danger btn-sm" wire:click="deleteTodo({{ $todo->id }})">
                    <i class="fa fa-trash"></i>
                </button>
            </li>
        @endforeach
    </ul>
</div>
