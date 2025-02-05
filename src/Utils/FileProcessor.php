<?php

namespace App\Utils;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;

class FileProcessor {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function processSpreadsheetToDatabase($filePath, $listId) {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Get headers from first row
            $headers = array_map('strtolower', array_map('trim', array_filter($rows[0])));
            
            // Begin transaction
            $this->db->beginTransaction();
            
            // Prepare insert statement
            $sql = "INSERT INTO list_records (list_id, email, first_name, last_name, custom_fields) 
                    VALUES (:list_id, :email, :first_name, :last_name, :custom_fields)";
            $stmt = $this->db->prepare($sql);

            // Process each row
            for ($i = 1; $i < count($rows); $i++) {
                $row = array_combine($headers, $rows[$i]);
                
                // Extract known fields
                $email = $row['email'] ?? '';
                $firstName = $row['first_name'] ?? $row['firstname'] ?? '';
                $lastName = $row['last_name'] ?? $row['lastname'] ?? '';
                
                // Remove known fields and store rest as custom fields
                unset($row['email'], $row['first_name'], $row['firstname'], $row['last_name'], $row['lastname']);
                $customFields = json_encode(array_filter($row));

                // Insert record
                $stmt->execute([
                    'list_id' => $listId,
                    'email' => $email,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'custom_fields' => $customFields
                ]);
            }

            $this->db->commit();
            return count($rows) - 1;

        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function processDocumentToDatabase($filePath, $listId) {
        try {
            $phpWord = WordIOFactory::load($filePath);
            $records = [];
            
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text = trim($element->getText());
                        if (filter_var($text, FILTER_VALIDATE_EMAIL)) {
                            $records[] = ['email' => $text];
                        }
                    }
                }
            }

            // Begin transaction
            $this->db->beginTransaction();
            
            $sql = "INSERT INTO list_records (list_id, email) VALUES (:list_id, :email)";
            $stmt = $this->db->prepare($sql);

            foreach ($records as $record) {
                $stmt->execute([
                    'list_id' => $listId,
                    'email' => $record['email']
                ]);
            }

            $this->db->commit();
            return count($records);

        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function removeDuplicates($listId) {
        $sql = "DELETE t1 FROM list_records t1
                INNER JOIN list_records t2
                WHERE t1.list_id = :list_id 
                AND t1.id > t2.id 
                AND t1.email = t2.email";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['list_id' => $listId]);
    }

    public function exportToCSV($listId, $outputPath) {
        $sql = "SELECT * FROM list_records WHERE list_id = :list_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['list_id' => $listId]);
        $records = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $fp = fopen($outputPath, 'w');
        
        // Write headers
        fputcsv($fp, ['Email', 'First Name', 'Last Name', 'Custom Fields']);
        
        // Write data
        foreach ($records as $record) {
            fputcsv($fp, [
                $record['email'],
                $record['first_name'],
                $record['last_name'],
                $record['custom_fields']
            ]);
        }
        
        fclose($fp);
    }
} 