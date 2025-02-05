<?php require_once __DIR__ . '/../layouts/app.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Email Templates</h1>
        <a href="/dashboard/templates/create" 
           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            <i class="fas fa-plus mr-2"></i> Create Template
        </a>
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

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($templates as $template): ?>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold">
                            <?php echo htmlspecialchars($template['name']); ?>
                            <?php if ($template['is_default']): ?>
                                <span class="ml-2 px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">Default</span>
                            <?php endif; ?>
                        </h3>
                    </div>
                    <div class="aspect-w-16 aspect-h-9 mb-4">
                        <iframe src="/dashboard/templates/preview/<?php echo $template['id']; ?>" 
                                class="w-full h-full border rounded">
                        </iframe>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <?php if (!$template['is_default']): ?>
                            <a href="/dashboard/templates/edit/<?php echo $template['id']; ?>" 
                               class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <form action="/dashboard/templates/delete/<?php echo $template['id']; ?>" 
                                  method="POST" class="inline">
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to delete this template?')"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    <i class="fas fa-trash mr-1"></i> Delete
                                </button>
                            </form>
                        <?php endif; ?>
                        <button onclick="duplicateTemplate(<?php echo $template['id']; ?>)"
                                class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">
                            <i class="fas fa-copy mr-1"></i> Duplicate
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
function duplicateTemplate(id) {
    fetch(`/dashboard/templates/${id}`)
        .then(response => response.json())
        .then(template => {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/dashboard/templates/store';
            
            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'name';
            nameInput.value = `Copy of ${template.name}`;
            
            const htmlInput = document.createElement('input');
            htmlInput.type = 'hidden';
            htmlInput.name = 'html_content';
            htmlInput.value = template.html_content;
            
            const cssInput = document.createElement('input');
            cssInput.type = 'hidden';
            cssInput.name = 'css_content';
            cssInput.value = template.css_content;
            
            const jsInput = document.createElement('input');
            jsInput.type = 'hidden';
            jsInput.name = 'js_content';
            jsInput.value = template.js_content;
            
            form.appendChild(nameInput);
            form.appendChild(htmlInput);
            form.appendChild(cssInput);
            form.appendChild(jsInput);
            
            document.body.appendChild(form);
            form.submit();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to duplicate template');
        });
}
</script> 