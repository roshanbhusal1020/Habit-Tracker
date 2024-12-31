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
            </div>

        </div>

        
        </div>
    </div>
</x-app-layout>