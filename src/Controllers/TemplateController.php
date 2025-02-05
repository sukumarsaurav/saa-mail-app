<?php

namespace App\Controllers;

use App\Models\Template;

class TemplateController {
    private $template;
    
    public function __construct() {
        $this->template = new Template();
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $templates = $this->template->getUserTemplates($userId);
        require_once __DIR__ . '/../../views/dashboard/templates.php';
    }

    public function create() {
        require_once __DIR__ . '/../../views/dashboard/template-editor.php';
    }

    public function store() {
        try {
            $userId = $_SESSION['user_id'];
            $data = [
                'name' => $_POST['name'],
                'html_content' => $_POST['html_content'],
                'css_content' => $_POST['css_content'] ?? '',
                'js_content' => $_POST['js_content'] ?? '',
            ];

            if ($this->template->createTemplate($userId, $data)) {
                $_SESSION['success'] = 'Template created successfully';
            } else {
                throw new \Exception('Failed to create template');
            }

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /dashboard/templates');
        exit;
    }

    public function edit($id) {
        $userId = $_SESSION['user_id'];
        $template = $this->template->getTemplate($id, $userId);
        
        if (!$template) {
            $_SESSION['error'] = 'Template not found';
            header('Location: /dashboard/templates');
            exit;
        }

        require_once __DIR__ . '/../../views/dashboard/template-editor.php';
    }

    public function update($id) {
        try {
            $userId = $_SESSION['user_id'];
            $data = [
                'name' => $_POST['name'],
                'html_content' => $_POST['html_content'],
                'css_content' => $_POST['css_content'] ?? '',
                'js_content' => $_POST['js_content'] ?? '',
            ];

            if ($this->template->updateTemplate($id, $userId, $data)) {
                $_SESSION['success'] = 'Template updated successfully';
            } else {
                throw new \Exception('Failed to update template');
            }

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /dashboard/templates');
        exit;
    }

    public function delete($id) {
        try {
            $userId = $_SESSION['user_id'];
            if ($this->template->deleteTemplate($id, $userId)) {
                $_SESSION['success'] = 'Template deleted successfully';
            } else {
                throw new \Exception('Failed to delete template');
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /dashboard/templates');
        exit;
    }

    public function preview($id) {
        $userId = $_SESSION['user_id'];
        $template = $this->template->getTemplate($id, $userId);
        
        if (!$template) {
            header('HTTP/1.0 404 Not Found');
            echo 'Template not found';
            exit;
        }

        echo $this->renderTemplate($template);
        exit;
    }

    private function renderTemplate($template) {
        return "
            <!DOCTYPE html>
            <html>
            <head>
                <style>{$template['css_content']}</style>
            </head>
            <body>
                {$template['html_content']}
                <script>{$template['js_content']}</script>
            </body>
            </html>
        ";
    }
}
