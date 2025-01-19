<x-guest-layout>
    <!-- Hero Section -->
    <div class="relative min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16">
            <!-- Hero Content -->
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl dark:text-white">
                    Redesign Your Day, <span class="text-indigo-600 dark:text-indigo-400">Your Way</span>
                </h1>
                <p class="mt-6 text-lg text-gray-500 sm:text-xl max-w-2xl mx-auto dark:text-gray-300">
                    Stay focused, track your progress, and achieve your goals with tools designed to make self-improvement easy and enjoyable.
                </p>
                
                <!-- CTA Button -->
                <div class="mt-8">
                    <a href="{{ route('register') }}" 
                       class="inline-flex items-center justify-center px-8 py-3 text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg md:text-lg">
                        Start Your Journey
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Feature Grid -->
            <div class="mt-24 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Habit Table -->
                <div class="p-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-200">
                    <div class="text-indigo-600 dark:text-indigo-400 text-3xl mb-4">
                        <i class="fas fa-table-list"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Habit Tracker</h3>
                    <p class="mt-3 text-gray-500 dark:text-gray-300">Build lasting habits with our intuitive tracking system. Monitor your daily routines and celebrate your consistency.</p>
                </div>

                <!-- Pomodoro Timer -->
                <div class="p-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-200">
                    <div class="text-indigo-600 dark:text-indigo-400 text-3xl mb-4">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Pomodoro Timer</h3>
                    <p class="mt-3 text-gray-500 dark:text-gray-300">Boost productivity with focused work sessions. Our customizable timer helps you maintain peak concentration.</p>
                </div>

                <!-- Todo List -->
                <div class="p-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-200">
                    <div class="text-indigo-600 dark:text-indigo-400 text-3xl mb-4">
                        <i class="fa-solid fa-rectangle-list"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Smart To-Do List</h3>
                    <p class="mt-3 text-gray-500 dark:text-gray-300">Organize tasks efficiently with our intelligent to-do list. Prioritize, schedule, and accomplish more each day.</p>
                </div>

                <!-- Journal -->
                <div class="p-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-200">
                    <div class="text-indigo-600 dark:text-indigo-400 text-3xl mb-4">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Digital Journal</h3>
                    <p class="mt-3 text-gray-500 dark:text-gray-300">Reflect and grow with our journaling tools. Capture your thoughts, track your mood, and document your progress.</p>
                </div>

                <!-- Progress Charts -->
                <div class="p-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg transform hover:scale-105 transition-all duration-200">
                    <div class="text-indigo-600 dark:text-indigo-400 text-3xl mb-4">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Progress Analytics</h3>
                    <p class="mt-3 text-gray-500 dark:text-gray-300">Visualize your journey with detailed charts and insights. Track trends and celebrate your improvements over time.</p>
                </div>
            </div>

            <!-- Bottom CTA -->
            <div class="mt-16 text-center pb-16">
                <p class="text-gray-500 dark:text-gray-400 mb-4">Ready to transform your daily routine?</p>
                <a href="{{ route('register') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 text-base font-medium rounded-md text-indigo-600 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-800 transform hover:scale-105 transition-all duration-200">
                    Get Started Free
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>