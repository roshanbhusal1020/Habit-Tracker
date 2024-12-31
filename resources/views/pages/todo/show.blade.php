<!-- resources/views/todos/index.blade.php -->
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Add Todo Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('todos.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4">
                        @csrf
                        <input 
                            type="text" 
                            name="task" 
                            class="form-input rounded-md shadow-sm flex-1"
                            placeholder="Add a new task..."
                            required
                        >
                        <input 
                            type="date" 
                            name="due_date"
                            class="form-input rounded-md shadow-sm"
                        >
                        <select 
                            name="priority"
                            class="form-select rounded-md shadow-sm"
                        >
                            <option value="0">Low Priority</option>
                            <option value="1">Medium Priority</option>
                            <option value="2">High Priority</option>
                        </select>
                        <button 
                            type="submit"
                            class="bg-red-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded"
                        >
                            Add Task
                        </button>
                    </form>
                </div>
                <div>
                    <table>
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Due Date</th>
                                <th> Priority</th>
                                <th>Status</th>
                                <th>Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($todos as  $todo)
                            <tr>
                                <td>{{$todo->task}}</td>
                                <td>{{$todo->due_date}}</td>
                                <td> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $todo->priority == 0 ? 'bg-gray-100 text-gray-800' : '' }}
                                            {{ $todo->priority == 1 ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $todo->priority == 2 ? 'bg-red-100 text-red-800' : '' }}">
                                            {{ ['Low', 'Medium', 'High'][$todo->priority] }} 
                                            <!-- This basically an array. so if priority value is 2, it will show High and if its 0 then Low. noting complicated -->
                                        </span></td>
                                
                                <td>
                                <form action="{{ route('todos.toggle', $todo->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <!-- <input type="hidden" name="id" value="{{ $todo->id }}">  -->
                                    <button type="submit">
                                        {{ $todo->completed ? 'DONE' : 'Mark Complete' }}
                                    </button>
                                </form>
                                </td>

                                <td>
                                    <form action="{{route('todos.destroy', $todo)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            
                            </tr>    

                            @endforeach
                            
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        
        </div>
    </div>
</x-app-layout>