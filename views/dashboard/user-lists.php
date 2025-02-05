<?php require_once __DIR__ . '/../layouts/app.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">User Lists</h1>
        <button onclick="document.getElementById('uploadModal').classList.remove('hidden')"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            <i class="fas fa-plus mr-2"></i> Upload New List
        </button>
    </div>

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

    <!-- User Lists Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($lists as $list): ?>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold"><?php echo htmlspecialchars($list['name']); ?></h3>
                        <p class="text-sm text-gray-500">
                            <?php echo number_format($list['total_records']); ?> records
                        </p>
                    </div>
                    <div class="text-gray-400">
                        <i class="fas fa-file-alt text-2xl"></i>
                    </div>
                </div>
                <div class="text-sm text-gray-600 mb-4">
                    Uploaded on <?php echo date('M j, Y', strtotime($list['created_at'])); ?>
                </div>
                <div class="flex space-x-2">
                    <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                        <i class="fas fa-download mr-1"></i> Download
                    </button>
                    <form action="/user-lists/delete/<?php echo $list['id']; ?>" method="POST" class="inline">
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                                onclick="return confirm('Are you sure you want to delete this list?')">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Upload New List</h2>
                        <button onclick="document.getElementById('uploadModal').classList.add('hidden')"
                                class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form action="/user-lists/store" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">List Name</label>
                            <input type="text" name="name" required
                                   class="w-full px-3 py-2 border rounded-md">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">File</label>
                            <input type="file" name="file" required accept=".xlsx,.xls,.csv,.docx"
                                   class="w-full px-3 py-2 border rounded-md">
                            <p class="text-sm text-gray-500 mt-1">
                                Supported formats: XLSX, XLS, CSV, DOCX
                            </p>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="button"
                                    onclick="document.getElementById('uploadModal').classList.add('hidden')"
                                    class="px-4 py-2 text-gray-600 hover:text-gray-800">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> 