<?php require_once __DIR__ . '/../layouts/app.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-8 flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">
            <?php echo isset($template) ? 'Edit Template' : 'Create Template'; ?>
        </h1>
        <a href="/dashboard/templates" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Templates
        </a>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form id="templateForm" method="POST" action="<?php echo isset($template) ? '/dashboard/templates/update/' . $template['id'] : '/dashboard/templates/store'; ?>">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Template Name</label>
                <input type="text" name="name" required
                       value="<?php echo isset($template) ? htmlspecialchars($template['name']) : ''; ?>"
                       class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <!-- HTML Editor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">HTML</label>
                        <div class="border rounded-md">
                            <div id="htmlEditor" class="h-64"><?php echo isset($template) ? htmlspecialchars($template['html_content']) : ''; ?></div>
                            <textarea name="html_content" id="htmlContent" class="hidden"></textarea>
                        </div>
                    </div>

                    <!-- CSS Editor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">CSS</label>
                        <div class="border rounded-md">
                            <div id="cssEditor" class="h-64"><?php echo isset($template) ? htmlspecialchars($template['css_content']) : ''; ?></div>
                            <textarea name="css_content" id="cssContent" class="hidden"></textarea>
                        </div>
                    </div>

                    <!-- JavaScript Editor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">JavaScript</label>
                        <div class="border rounded-md">
                            <div id="jsEditor" class="h-64"><?php echo isset($template) ? htmlspecialchars($template['js_content']) : ''; ?></div>
                            <textarea name="js_content" id="jsContent" class="hidden"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Live Preview -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Live Preview</label>
                    <div class="border rounded-md bg-white h-[800px]">
                        <iframe id="previewFrame" class="w-full h-full"></iframe>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="/dashboard/templates" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    <?php echo isset($template) ? 'Update Template' : 'Create Template'; ?>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Load Ace Editor -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
<script src="/assets/js/template-editor.js"></script> 