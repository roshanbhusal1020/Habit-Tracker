<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header with Add Button -->
            <div class=" flex justify-between items-center">
                <h2 class="text-2xl font-bold">December 2024</h2>
                <button class="bg-red-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                    Add New Habit
                </button>
            </div>

            <!-- Main Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Working Hours
                                </th>
                                @foreach($habits as $habit)
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" >{{ $habit->name }}</th>
                                    
                                @endforeach
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mood
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Notes
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Day 1 -->
                        


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>