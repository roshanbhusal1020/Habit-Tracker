<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>


            <div class="notification-container">
                <div id="notificationBell" class="notification-bell">
                    <i id = "notificationIcon"class="fa fa-bell"></i>
                    <span id="notificationBadge" class="notification-badge"></span>
                </div>
                <div id="notificationDropdown" class="notification-dropdown">
                    <div class="notification-header">
                        <h2>Notifications</h2>
                        <button id="muteButton" class="mute-button" title="Mute notifications">
                            <i class="fa fa-bell"></i>
                        </button>
                    </div>
                    <button id="markAllRead" class="mark-all-read">Mark all as read</button>
                    <div class="notification-list">
                        <div id="loadingSpinner" class="loading-spinner"></div> 
                        <!-- I dont need spinner, I should just remove this -->
                        <div id="notificationsContainer"></div>
                    </div>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    const notificationBell = document.getElementById("notificationBell");
    const notificationDropdown = document.getElementById("notificationDropdown");
    const notificationsContainer = document.getElementById("notificationsContainer");
    const loadingSpinner = document.getElementById("loadingSpinner");
    const notificationBadge = document.getElementById("notificationBadge");

    let notificationIds = [];
    let pageNumber = 0; 
    
    let isDropdownOpen = false;
    fetchNotifications();

    // Toggle dropdown visibility
    notificationBell.addEventListener("click", function () {
        isDropdownOpen = !isDropdownOpen;
        notificationDropdown.style.display = isDropdownOpen ? "block" : "none";
    });

    function fetchNotifications() {
    notificationsContainer.innerHTML = "";
    loadingSpinner.style.display = "block";

    fetch("/notification")  // Changed from /notification to /notifications
        .then((response) => response.json())
        .then((data) => {
            loadingSpinner.style.display = "none";
            if (data.data.length === 0) {
                notificationsContainer.innerHTML = "<p>No notifications.</p>";
            } else {
                let unreadNotifications = 0; 
                data.data.forEach((notification) => {
                    notificationIds.push(notification.id)
                    if(!notification.read){
                        unreadNotifications++;
                    }

                    const notificationItem = document.createElement("div");
                    notificationItem.classList.add("notification-item");
                    notificationItem.innerHTML = `
                    <a href="${notification.url}">
                        <p>${notification.message}</p> <!-- Changed from content to message -->
                        <p class="text-sm text-gray-500">${notification.created_at}</p>
                    </a>
                    `;
                    notificationsContainer.appendChild(notificationItem);
                });

                if (unreadNotifications > 0) {
                    notificationBadge.innerHTML = unreadNotifications;
                    notificationBadge.style.display = "inline-block";
                } else {
                    notificationBadge.style.display = "none";
                }
            }
        })
        .catch((error) => {
            loadingSpinner.style.display = "none";
            console.error("Error fetching notifications:", error);
            notificationsContainer.innerHTML = "<p>Error loading notifications.</p>";
        });
}

    notificationDropdown.addEventListener("scroll", function () {
//scroll height = total height of a notification container
// scroll top = how much have I scrolled down
// client height = how much can be seen atm
        if (notificationDropdown.scrollHeight - notificationDropdown.scrollTop <= notificationDropdown.clientHeight) {
            console.log("User scrolled to the bottom!");
            pageNumber ++

            getNewNotifications()

            // You can trigger a function here, e.g., load more notifications
        }
    });

    function getNewNotifications() {
    fetch("/notification?page=" + pageNumber)
        .then(response => response.json())
        .then(data => {
            loadingSpinner.style.display = "none";
            data.data.forEach(notification => {
                if (!notificationIds.includes(notification.id)) {
                    console.log(notification)
                const notificationItem = document.createElement("div");
                notificationItem.classList.add("notification-item");
                notificationItem.innerHTML = `
                    <a href="${notification.url}">
                        <p>${notification.id} </p>
                        <p>${notification.content}</p>
                    </a>
                `;
                notificationIds.push(notification.id)
                notificationsContainer.appendChild(notificationItem);
                }

               
            });
        })
        .catch(error => {
            loadingSpinner.style.display = "none";
            console.error("Error fetching notifications:", error);
            notificationsContainer.innerHTML = "<p>Error loading notifications.</p>";
        });
}
document.addEventListener('click', function(event) {
        if (!notificationBell.contains(event.target) && !notificationDropdown.contains(event.target)) {
            notificationDropdown.style.display = 'none';
            isDropdownOpen = false;
        }
    });

    function markSingleRead() {
        
    }
});

</script>