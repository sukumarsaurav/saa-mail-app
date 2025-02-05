<?php require_once __DIR__ . '/../layouts/app.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-6">SMTP Settings</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- SMTP Settings List -->
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-4">Your SMTP Configurations</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($settings as $setting): ?>
                    <div class="border rounded-lg p-4">
                        <h4 class="font-bold"><?php echo htmlspecialchars($setting['name']); ?></h4>
                        <p class="text-sm text-gray-600">Host: <?php echo htmlspecialchars($setting['smtp_host']); ?></p>
                        <p class="text-sm text-gray-600">Port: <?php echo htmlspecialchars($setting['smtp_port']); ?></p>
                        <p class="text-sm text-gray-600">Username: <?php echo htmlspecialchars($setting['smtp_username']); ?></p>
                        
                        <div class="mt-4 flex space-x-2">
                            <button onclick="editSetting(<?php echo $setting['id']; ?>)" 
                                    class="bg-blue-500 text-white px-3 py-1 rounded">
                                Edit
                            </button>
                            <form action="/settings/delete/<?php echo $setting['id']; ?>" method="POST" class="inline">
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded"
                                        onclick="return confirm('Are you sure you want to delete this configuration?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Add New SMTP Configuration Form -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-xl font-semibold mb-4">Add New SMTP Configuration</h3>
            <form action="/settings/store" method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Configuration Name</label>
                        <input type="text" name="name" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">SMTP Host</label>
                        <input type="text" name="smtp_host" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">SMTP Port</label>
                        <input type="number" name="smtp_port" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">SMTP Username</label>
                        <input type="text" name="smtp_username" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">SMTP Password</label>
                        <input type="password" name="smtp_password" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                        Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editSetting(settingId) {
    // Implement edit functionality using JavaScript/AJAX
    // You could show a modal with the edit form
    // or redirect to an edit page
}
</script> 