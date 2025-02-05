<?php

namespace App\Controllers;

use App\Models\UserList;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserListController {
    private $userList;
    private $uploadDir;
    
    public function __construct() {
        $this->userList = new UserList();
        $this->uploadDir = __DIR__ . '/../../storage/uploads/lists/';
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $lists = $this->userList->getUserLists($userId);
        
        require_once __DIR__ . '/../../views/dashboard/user-lists.php';
    }

    public function store() {
        try {
            if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception('No file uploaded or upload error');
            }

            $file = $_FILES['file'];
            $fileName = $this->generateSecureFileName($file['name']);
            $filePath = $this->uploadDir . $fileName;

            // Validate file type
            $this->validateFileType($file['name']);

            // Move uploaded file
            if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                throw new \Exception('Failed to move uploaded file');
            }

            // Process file based on type
            $totalRecords = $this->processFile($filePath, $file['name']);

            // Save to database
            $userId = $_SESSION['user_id'];
            $result = $this->userList->createList(
                $userId,
                $_POST['name'],
                $fileName,
                $totalRecords
            );

            if ($result) {
                $_SESSION['success'] = 'User list uploaded successfully';
            } else {
                throw new \Exception('Failed to save list information');
            }

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /dashboard/user-lists');
        exit;
    }

    private function validateFileType($fileName) {
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedTypes = ['xlsx', 'xls', 'csv', 'docx'];

        if (!in_array($extension, $allowedTypes)) {
            throw new \Exception('Invalid file type. Allowed types: XLSX, XLS, CSV, DOCX');
        }
    }

    private function generateSecureFileName($originalName) {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        return uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
    }

    private function processFile($filePath, $originalName) {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        
        if (in_array($extension, ['xlsx', 'xls', 'csv'])) {
            return $this->processSpreadsheet($filePath, $extension);
        } elseif ($extension === 'docx') {
            return $this->processDocument($filePath);
        }
        
        throw new \Exception('Unsupported file type');
    }

    private function processSpreadsheet($filePath, $extension) {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = array_filter($worksheet->toArray());
            return count($rows) - 1; // Subtract header row
        } catch (\Exception $e) {
            throw new \Exception('Error processing spreadsheet: ' . $e->getMessage());
        }
    }

    private function processDocument($filePath) {
        try {
            $phpWord = IOFactory::load($filePath);
            $count = 0;
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $count++;
                    }
                }
            }
            return $count;
        } catch (\Exception $e) {
            throw new \Exception('Error processing document: ' . $e->getMessage());
        }
    }

    public function delete($listId) {
        try {
            $userId = $_SESSION['user_id'];
            $list = $this->userList->getList($listId, $userId);

            if (!$list) {
                throw new \Exception('List not found');
            }

            // Delete file
            $filePath = $this->uploadDir . $list['file_name'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Delete from database
            $result = $this->userList->deleteList($listId, $userId);

            if ($result) {
                $_SESSION['success'] = 'User list deleted successfully';
            } else {
                throw new \Exception('Failed to delete list');
            }

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /dashboard/user-lists');
        exit;
    }

    public function download($listId) {
        try {
            $userId = $_SESSION['user_id'];
            $list = $this->userList->getList($listId, $userId);

            if (!$list) {
                throw new \Exception('List not found');
            }

            $filePath = $this->uploadDir . $list['file_name'];
            if (!file_exists($filePath)) {
                throw new \Exception('File not found');
            }

            // Get file extension
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            
            // Set appropriate headers
            $mimeTypes = [
                'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'xls' => 'application/vnd.ms-excel',
                'csv' => 'text/csv',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];

            header('Content-Type: ' . ($mimeTypes[$extension] ?? 'application/octet-stream'));
            header('Content-Disposition: attachment; filename="' . $list['name'] . '.' . $extension . '"');
            header('Content-Length: ' . filesize($filePath));
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            readfile($filePath);
            exit;

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /dashboard/user-lists');
            exit;
        }
    }
}
