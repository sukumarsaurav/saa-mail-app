<?php require_once __DIR__ . '/../layouts/app.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Template Categories</h1>
        <button id="newCategoryBtn" 
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            <i class="fas fa-plus mr-2"></i> New Category
        </button>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($categories as $category): ?>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </h3>
                        <p class="text-sm text-gray-500">
                            <?php echo $category['template_count']; ?> templates
                        </p>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="editCategory(<?php echo $category['id']; ?>)"
                                class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteCategory(<?php echo $category['id']; ?>)"
                                class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <?php foreach ($category['templates'] as $template): ?>
                        <div class="flex items-center justify-between py-2 border-b">
                            <span class="text-sm text-gray-700">
                                <?php echo htmlspecialchars($template['name']); ?>
                            </span>
                            <a href="/dashboard/templates/edit/<?php echo $template['id']; ?>"
                               class="text-blue-500 hover:text-blue-700">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Category Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="bg-white rounded-lg max-w-md mx-auto mt-20 p-6">
            <h2 class="text-xl font-bold mb-4" id="modalTitle">New Category</h2>
            <form id="categoryForm">
                <input type="hidden" name="category_id" id="categoryId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Category Name
                    </label>
                    <input type="text" name="name" id="categoryName" required
                           class="w-full px-3 py-2 border rounded-md">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()"
                            class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/assets/js/template-categories.js"></script> 