<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Marketing Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="/assets/js/dashboard.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="bg-gray-800 text-white w-64 min-h-screen flex-shrink-0 hidden md:block">
            <div class="p-4">
                <h1 class="text-2xl font-bold">Email Marketing</h1>
            </div>
            <nav class="mt-4">
                <a href="/dashboard" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?php echo $_SERVER['REQUEST_URI'] === '/dashboard' ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>
                <a href="/dashboard/user-lists" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?php echo strpos($_SERVER['REQUEST_URI'], '/user-lists') !== false ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-users mr-2"></i> User Lists
                </a>
                <a href="/dashboard/templates" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?php echo strpos($_SERVER['REQUEST_URI'], '/templates') !== false ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-file-alt mr-2"></i> Templates
                </a>
                <a href="/dashboard/campaigns" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?php echo strpos($_SERVER['REQUEST_URI'], '/campaigns') !== false ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-paper-plane mr-2"></i> Campaigns
                </a>
                <a href="/dashboard/settings" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 <?php echo strpos($_SERVER['REQUEST_URI'], '/settings') !== false ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-cog mr-2"></i> Settings
                </a>
            </nav>
            <div class="absolute bottom-0 w-64 bg-gray-900">
                <div class="p-4">
                    <div class="flex items-center">
                        <img src="<?php echo isset($_SESSION['user_avatar']) ? $_SESSION['user_avatar'] : '/assets/images/default-avatar.png'; ?>" 
                             alt="Profile" 
                             class="w-8 h-8 rounded-full mr-2">
                        <div class="flex-1">
                            <p class="text-sm font-medium"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></p>
                            <a href="/logout" class="text-xs text-gray-400 hover:text-white">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Navigation -->
        <div class="md:hidden fixed bottom-0 w-full bg-gray-800 text-white z-50">
            <div class="flex justify-around py-2">
                <a href="/dashboard" class="text-center">
                    <i class="fas fa-home text-xl"></i>
                    <span class="block text-xs">Home</span>
                </a>
                <a href="/dashboard/user-lists" class="text-center">
                    <i class="fas fa-users text-xl"></i>
                    <span class="block text-xs">Lists</span>
                </a>
                <a href="/dashboard/templates" class="text-center">
                    <i class="fas fa-file-alt text-xl"></i>
                    <span class="block text-xs">Templates</span>
                </a>
                <a href="/dashboard/campaigns" class="text-center">
                    <i class="fas fa-paper-plane text-xl"></i>
                    <span class="block text-xs">Campaigns</span>
                </a>
                <a href="/dashboard/settings" class="text-center">
                    <i class="fas fa-cog text-xl"></i>
                    <span class="block text-xs">Settings</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between p-4">
                    <button id="mobile-menu-button" class="md:hidden p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button id="notification-button" class="relative p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none">
                                <i class="fas fa-bell text-xl"></i>
                                <span id="notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center hidden">
                                    0
                                </span>
                            </button>
                        </div>
                        <div class="md:hidden">
                            <img src="<?php echo isset($_SESSION['user_avatar']) ? $_SESSION['user_avatar'] : '/assets/images/default-avatar.png'; ?>" 
                                 alt="Profile" 
                                 class="w-8 h-8 rounded-full">
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <?php if (isset($_SESSION['flash_message'])): ?>
                    <div class="p-4">
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            <?php 
                                echo $_SESSION['flash_message'];
                                unset($_SESSION['flash_message']);
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['flash_error'])): ?>
                    <div class="p-4">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <?php 
                                echo $_SESSION['flash_error'];
                                unset($_SESSION['flash_error']);
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Page Content -->
                <?php echo $content ?? ''; ?>
            </main>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-50 hidden">
        <div class="bg-white w-64 min-h-screen">
            <!-- Mobile menu content -->
        </div>
    </div>

    <!-- Add the notifications dropdown -->
    <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50">
        <div class="px-4 py-2 border-b">
            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
        </div>
        <div id="notification-list" class="max-h-96 overflow-y-auto">
            <!-- Notifications will be dynamically inserted here -->
        </div>
        <div class="px-4 py-2 border-t">
            <a href="/notifications" class="text-sm text-blue-500 hover:text-blue-700">View all notifications</a>
        </div>
    </div>

    <!-- Add the mobile menu overlay -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>

    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        function toggleNotifications() {
            // Implement notifications dropdown
        }
    </script>
</body>
</html> 