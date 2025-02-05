<?php

namespace App\Controllers;

use App\Models\Setting;

class SettingsController {
    private $setting;
    
    public function __construct() {
        $this->setting = new Setting();
    }

    public function index() {
        $userId = $_SESSION['user_id']; // Assuming you store user_id in session
        $settings = $this->setting->getUserSettings($userId);
        
        require_once __DIR__ . '/../../views/dashboard/settings.php';
    }

    public function store() {
        try {
            if (!$this->validateSmtpInput($_POST)) {
                throw new \Exception('Invalid input data');
            }

            $userId = $_SESSION['user_id'];
            $result = $this->setting->createSmtpSetting($_POST, $userId);

            if ($result) {
                $_SESSION['success'] = 'SMTP configuration saved successfully';
            } else {
                $_SESSION['error'] = 'Failed to save SMTP configuration';
            }

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /dashboard/settings');
        exit;
    }

    public function update($settingId) {
        try {
            if (!$this->validateSmtpInput($_POST)) {
                throw new \Exception('Invalid input data');
            }

            $userId = $_SESSION['user_id'];
            $result = $this->setting->updateSmtpSetting($_POST, $settingId, $userId);

            if ($result) {
                $_SESSION['success'] = 'SMTP configuration updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update SMTP configuration';
            }

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /dashboard/settings');
        exit;
    }

    public function delete($settingId) {
        try {
            $userId = $_SESSION['user_id'];
            $result = $this->setting->deleteSetting($settingId, $userId);

            if ($result) {
                $_SESSION['success'] = 'SMTP configuration deleted successfully';
            } else {
                $_SESSION['error'] = 'Failed to delete SMTP configuration';
            }

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /dashboard/settings');
        exit;
    }

    private function validateSmtpInput($data) {
        return !empty($data['name']) &&
               !empty($data['smtp_host']) &&
               !empty($data['smtp_port']) &&
               !empty($data['smtp_username']) &&
               !empty($data['smtp_password']) &&
               filter_var($data['smtp_port'], FILTER_VALIDATE_INT);
    }
}
