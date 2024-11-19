<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\Todo; // Make sure to import the Todo model

class TodoList extends Component
{
    public $newTodo;
    public $todos = [];

    // Load todos from the database when the component is mounted
    public function mount()
    {
        $this->todos = Todo::all();
    }

    // Add a new todo
    public function addTodo()
    {
        // Validate the new todo input
        $this->validate([
            'newTodo' => 'required|string|max:255',
        ]);

        // Create a new todo in the database
        Todo::create([
            'task' => $this->newTodo,
        ]);

        // Clear the input field
        $this->newTodo = '';

        // Refresh the todo list
        $this->todos = Todo::all();
    }

    // Toggle the completion status of a todo
    public function toggleCompletion($todoId)
    {
        $todo = Todo::find($todoId);
        if ($todo) {
            $todo->completed = !$todo->completed;  // Toggle completion
            $todo->save();
        }
    }

    // Delete a todo item
    public function deleteTodo($todoId)
    {
        $todo = Todo::find($todoId);
        if ($todo) {
            $todo->delete();  // Delete the todo item
            $this->todos = Todo::all();  // Refresh the list
        }
    }

    // Render the component view
    public function render()
    {
        return view('livewire.todo-list');
    }
}
