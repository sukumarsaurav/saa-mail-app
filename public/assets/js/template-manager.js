document.addEventListener('DOMContentLoaded', function() {
    // Template Export Handler
    const handleExport = async (templateId) => {
        try {
            const response = await fetch(`/dashboard/templates/export/${templateId}`);
            if (!response.ok) throw new Error('Export failed');
            
            const template = await response.json();
            
            // Create downloadable file
            const blob = new Blob([JSON.stringify(template, null, 2)], {
                type: 'application/json'
            });
            
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `template-${templateId}.json`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        } catch (error) {
            console.error('Export error:', error);
            alert('Failed to export template');
        }
    };

    // Template Import Handler
    const importForm = document.getElementById('importForm');
    if (importForm) {
        importForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(importForm);
            const fileInput = importForm.querySelector('input[type="file"]');
            
            if (!fileInput.files.length) {
                alert('Please select a template file');
                return;
            }

            try {
                const reader = new FileReader();
                reader.onload = async (e) => {
                    try {
                        const template = JSON.parse(e.target.result);
                        
                        const response = await fetch('/dashboard/templates/import', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(template)
                        });

                        if (!response.ok) throw new Error('Import failed');
                        
                        const result = await response.json();
                        if (result.success) {
                            window.location.reload();
                        } else {
                            throw new Error(result.error);
                        }
                    } catch (error) {
                        console.error('Import error:', error);
                        alert('Failed to import template: ' + error.message);
                    }
                };
                reader.readAsText(fileInput.files[0]);
            } catch (error) {
                console.error('File reading error:', error);
                alert('Failed to read template file');
            }
        });
    }
}); 