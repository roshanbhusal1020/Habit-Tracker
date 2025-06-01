<!-- resources/views/todos/index.blade.php -->
<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4 shadow-sm">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Add Todo Form -->
            <div class="bg-white overflow-hidden shadow-md rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4">Add New Task</h2>
                    <form action="{{ route('todos.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4">
                        @csrf
                        <input 
                            type="text" 
                            name="task" 
                            class="form-input rounded-md shadow-sm flex-1 border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            placeholder="Add a new task..."
                            required
                        >
                        <input 
                            type="date" 
                            name="due_date"
                            class="form-input rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >
                        <select 
                            name="priority"
                            class="form-select rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        >
                            <option value="0">Low Priority</option>
                            <option value="1">Medium Priority</option>
                            <option value="2">High Priority</option>
                        </select>
                        <button 
                            type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out shadow-sm"
                        >
                            Add Task
                        </button>
                    </form>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($todos as $todo)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{$todo->task}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($todo->due_date)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap"> 
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $todo->priority == 0 ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $todo->priority == 1 ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ $todo->priority == 2 ? 'bg-rose-100 text-rose-800' : '' }}">
                                        {{ ['Low', 'Medium', 'High'][$todo->priority] }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <form action="{{ route('todos.toggle', $todo->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-3 py-1 rounded-md text-sm font-medium 
                                            {{ $todo->completed 
                                                ? 'bg-green-100 text-green-800 hover:bg-green-200' 
                                                : 'bg-blue-100 text-blue-800 hover:bg-blue-200' }}
                                            transition duration-150 ease-in-out">
                                            {{ $todo->completed ? 'Completed' : 'Mark Complete' }}
                                        </button>
                                    </form>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <form action="{{route('todos.edit', $todo)}}" method="GET">
                                            <button type="submit" class="text-indigo-600 hover:text-indigo-900 px-2 py-1 rounded hover:bg-indigo-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        </form>

                                        <form action="{{route('todos.destroy', $todo)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-900 px-2 py-1 rounded hover:bg-rose-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>    
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>