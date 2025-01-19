<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habit Tracker Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 bg-white shadow-lg lg:w-64 w-3/4 transform lg:translate-x-0 transition-transform duration-200 ease-in-out"
               :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            <div class="flex items-center justify-between p-4 border-b">
                <h1 class="text-xl font-bold">Habit Tracker</h1>
                <button @click="sidebarOpen = false" class="lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <nav class="p-4">
                <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-100">Dashboard</a>
                <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-100">Habits</a>
                <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-100">Todo List</a>
                <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-100">Pomodoro Timer</a>
                <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-100">Journal</a>
                <a href="#" class="block py-2.5 px-4 rounded hover:bg-gray-100">Statistics</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="lg:ml-64">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between p-4">
                    <button @click="sidebarOpen = true" class="lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div x-data="{ notificationsOpen: false }" class="relative">
                            <button @click="notificationsOpen = !notificationsOpen" class="p-2 hover:bg-gray-100 rounded-full">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>
                            <!-- Notifications Dropdown -->
                            <div x-show="notificationsOpen" @click.away="notificationsOpen = false"
                                 class="absolute right-0 w-80 mt-2 bg-white rounded-lg shadow-lg">
                                <div class="p-4 border-b">
                                    <h3 class="font-semibold">Notifications</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <a href="#" class="block p-4 hover:bg-gray-50 border-b">
                                        <p class="text-sm">Don't forget to complete your daily habits!</p>
                                        <p class="text-xs text-gray-500 mt-1">2 hours ago</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- User Menu -->
                        <div x-data="{ userMenuOpen: false }" class="relative">
                            <button @click="userMenuOpen = !userMenuOpen" class="flex items-center space-x-2">
                                <img src="https://via.placeholder.com/40" alt="User" class="w-8 h-8 rounded-full">
                                <span>John Doe</span>
                            </button>
                            <!-- User Dropdown -->
                            <div x-show="userMenuOpen" @click.away="userMenuOpen = false"
                                 class="absolute right-0 w-48 mt-2 bg-white rounded-lg shadow-lg">
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Settings</a>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-semibold mb-4">Quick Actions</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <a href="#" class="flex flex-col items-center p-4 hover:bg-gray-50 rounded-lg">
                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Start Pomodoro</span>
                            </a>
                            <a href="#" class="flex flex-col items-center p-4 hover:bg-gray-50 rounded-lg">
                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span>Add Todo</span>
                            </a>
                            <a href="#" class="flex flex-col items-center p-4 hover:bg-gray-50 rounded-lg">
                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span>Journal Entry</span>
                            </a>
                            <a href="#" class="flex flex-col items-center p-4 hover:bg-gray-50 rounded-lg">
                                <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span>View Stats</span>
                            </a>
                        </div>
                    </div>

                    <!-- Progress & Tracking -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Today's Progress -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-lg font-semibold mb-4">Today's Progress</h2>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span>Habits Completed</span>
                                    <span class="font-bold">3/5</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span>Pomodoros</span>
                                    <span class="font-bold">2/4</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span>Todos</span>
                                    <span class="font-bold">5/8</span>
                                </div>
                            </div>
                        </div>

                        <!-- Mood & Productivity -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-lg font-semibold mb-4">Today's Tracking</h2>
                            <div class="space-y-6">
                                <div>
                                    <label class="block mb-2">Mood</label>
                                    <div class="flex gap-2">
                                        <button class="w-10 h-10 rounded-full border hover:bg-gray-50">1</button>
                                        <button class="w-10 h-10 rounded-full border hover:bg-gray-50">2</button>
                                        <button class="w-10 h-10 rounded-full border hover:bg-gray-50">3</button>
                                        <button class="w-10 h-10 rounded-full border hover:bg-gray-50">4</button>
                                        <button class="w-10 h-10 rounded-full border hover:bg-gray-50">5</button>
                                    </div>
                                </div>
                                <div>
                                    <label class="block mb-2">Productivity</label>
                                    <div class="flex gap-2">
                                        <button class="w-10 h-10 rounded-full border hover:bg-gray-50">1</button>
                                        <button class="w-10 h-10 rounded-full border hover:bg-gray-50">2</button>
                                        <button class="w-10 h-10 rounded-full border hover:bg-gray-50">3</button>
                                        <button class="w-10 h-10 rounded-full border hover:bg-gray-50">4</button>
                                        <button class="w-10 h-10 rounded-full border hover:bg-gray-50">5</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Weekly Overview -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-lg font-semibold mb-4">Weekly Overview</h2>
                            <div class="grid grid-cols-7 gap-2">
                                <div class="aspect-square p-2 text-center border rounded">
                                    <div class="text-sm text-gray-500">M</div>
                                    <div class="mt-1 w-2 h-2 mx-auto rounded-full bg-green-500"></div>
                                </div>
                                <div class="aspect-square p-2 text-center border rounded">
                                    <div class="text-sm text-gray-500">T</div>
                                    <div class="mt-1 w-2 h-2 mx-auto rounded-full bg-green-500"></div>
                                </div>
                                <div class="aspect-square p-2 text-center border rounded bg-blue-50">
                                    <div class="text-sm text-gray-500">W</div>
                                    <div class="mt-1 w-2 h-2 mx-auto rounded-full bg-green-500"></div>
                                </div>
                                <div class="aspect-square p-2 text-center border rounded">
                                    <div class="text-sm text-gray-500">T</div>
                                </div>
                                <div class="aspect-square p-2 text-center border rounded">
                                    <div class="text-sm text-gray-500">F</div>
                                </div>
                                <div class="aspect-square p-2 text-center border rounded">
                                    <div class="text-sm