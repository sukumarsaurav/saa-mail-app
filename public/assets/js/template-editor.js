document.addEventListener('DOMContentLoaded', function() {
    // Initialize Ace editors
    const htmlEditor = ace.edit("htmlEditor");
    htmlEditor.setTheme("ace/theme/monokai");
    htmlEditor.session.setMode("ace/mode/html");
    htmlEditor.setOptions({
        fontSize: "14px",
        showPrintMargin: false
    });

    const cssEditor = ace.edit("cssEditor");
    cssEditor.setTheme("ace/theme/monokai");
    cssEditor.session.setMode("ace/mode/css");
    cssEditor.setOptions({
        fontSize: "14px",
        showPrintMargin: false
    });

    const jsEditor = ace.edit("jsEditor");
    jsEditor.setTheme("ace/theme/monokai");
    jsEditor.session.setMode("ace/mode/javascript");
    jsEditor.setOptions({
        fontSize: "14px",
        showPrintMargin: false
    });

    // Update preview on editor changes
    let updateTimeout;
    const updatePreview = () => {
        clearTimeout(updateTimeout);
        updateTimeout = setTimeout(() => {
            const html = htmlEditor.getValue();
            const css = cssEditor.getValue();
            const js = jsEditor.getValue();

            // Update hidden form fields
            document.getElementById('htmlContent').value = html;
            document.getElementById('cssContent').value = css;
            document.getElementById('jsContent').value = js;

            // Update preview
            const preview = document.getElementById('previewFrame').contentDocument;
            preview.open();
            preview.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <style>${css}</style>
                </head>
                <body>
                    ${html}
                    <script>${js}<\/script>
                </body>
                </html>
            `);
            preview.close();
        }, 500);
    };

    // Add change listeners
    htmlEditor.session.on('change', updatePreview);
    cssEditor.session.on('change', updatePreview);
    jsEditor.session.on('change', updatePreview);

    // Initial preview
    updatePreview();

    // Form submission
    document.getElementById('templateForm').addEventListener('submit', function(e) {
        // Update hidden fields one last time before submission
        document.getElementById('htmlContent').value = htmlEditor.getValue();
        document.getElementById('cssContent').value = cssEditor.getValue();
        document.getElementById('jsContent').value = jsEditor.getValue();
    });

    // Add template variables button
    const addVariableButton = document.createElement('button');
    addVariableButton.type = 'button';
    addVariableButton.className = 'absolute top-2 right-2 px-2 py-1 text-sm bg-gray-700 text-white rounded';
    addVariableButton.textContent = 'Add Variable';
    addVariableButton.onclick = function() {
        const variables = [
            '{{first_name}}',
            '{{last_name}}',
            '{{email}}',
            '{{unsubscribe_url}}',
            '{{company_name}}',
            '{{current_year}}'
        ];
        
        const variable = prompt('Select or enter a variable:', variables.join('\n'));
        if (variable) {
            htmlEditor.insert(variable);
        }
    };

    document.querySelector('#htmlEditor').parentNode.style.position = 'relative';
    document.querySelector('#htmlEditor').parentNode.appendChild(addVariableButton);
}); 