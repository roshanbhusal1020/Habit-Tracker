<x-app-layout>
    <div>

    <form action="{{route('todos.update', $todo)}}" method="POST">
    @csrf
        @method('PATCH')
        <input name="task" type="text" value="{{$todo->task}}">
        <input name="due_date" type="date" value="{{$todo->due_date}}">

        <!-- <input name="priority" type="text" value="{{ ['Low', 'Medium', 'High'] [$todo->priority]}}"> -->

        <select name='priority'>
            <option value="0" {{ $todo->priority == 0 ? 'selected' : '' }}>Low</option>
            <option value="1" {{ $todo->priority == 1 ? 'selected' : '' }}>Medium</option>
            <option value="2" {{ $todo->priority == 2 ? 'selected' : '' }}>High</option>

        </select>

        <button type="submit">Update Task</button>
        <button href ="{{route('todo.index')}}"> Cancel</button>

    </form>
        
    </div>
</x-app-layout>