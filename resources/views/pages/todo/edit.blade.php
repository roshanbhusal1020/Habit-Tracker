<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-700 mb-6">Edit Task</h2>
                    
                    <form action="{{route('todos.update', $todo)}}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="space-y-6">
                            <div>
                                <label for="task" class="block text-sm font-medium text-gray-700 mb-1">Task</label>
                                <input 
                                    id="task"
                                    name="task" 
                                    type="text" 
                                    value="{{$todo->task}}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                >
                            </div>
                            
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                                <input 
                                    id="due_date"
                                    name="due_date" 
                                    type="date" 
                                    value="{{$todo->due_date}}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                >
                            </div>
                            
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                <select 
                                    id="priority"
                                    name="priority"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                    <option value="0" {{ $todo->priority == 0 ? 'selected' : '' }}>Low</option>
                                    <option value="1" {{ $todo->priority == 1 ? 'selected' : '' }}>Medium</option>
                                    <option value="2" {{ $todo->priority == 2 ? 'selected' : '' }}>High</option>
                                </select>
                            </div>
                            
                            <div class="flex justify-end space-x-3 pt-4">
                                <button 
                                    
                                    class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Update Task
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>