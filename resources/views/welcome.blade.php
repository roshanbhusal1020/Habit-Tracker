<x-guest-layout>
    <!-- Hero Section -->
    <div class="relative min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16">
            <div class="text-center">
                <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl dark:text-white">
                     Redesign Your Day, Your Way
                </h1>
                <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0 dark:text-gray-300">
                Stay focused, track your progress, and achieve your goals with tools designed to make self-improvement easy.
                </p>
                
                <!-- Call to Action Buttons -->
                <div class="mt-5 sm:mt-8 flex justify-center">
                    <div class="rounded-md shadow">
                        <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                            Get started
                        </a>
                    </div>
                </div>
            </div>

            <!-- Feature Grid -->
            <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Feature 1 -->
                <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Feature One</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-300">Description of your first feature.</p>
                </div>

                <!-- Feature 2 -->
                <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Feature Two</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-300">Description of your second feature.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Feature Three</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-300">Description of your third feature.</p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>