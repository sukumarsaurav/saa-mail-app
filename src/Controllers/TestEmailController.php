<?php

namespace App\Controllers;

use App\Models\Template;
use App\Models\Setting;
use App\Utils\TemplateProcessor;
use App\Utils\EmailSender;

class TestEmailController {
    private $template;
    private $setting;
    private $processor;
    private $emailSender;
    
    public function __construct() {
        $this->template = new Template();
        $this->setting = new Setting();
        $this->processor = new TemplateProcessor();
        $this->emailSender = new EmailSender();
    }
    
    public function sendTest($templateId) {
        try {
            $userId = $_SESSION['user_id'];
            
            // Get template
            $template = $this->template->getTemplate($templateId, $userId);
            if (!$template) {
                throw new \Exception('Template not found');
            }
            
            // Get user's SMTP settings
            $smtpSettings = $this->setting->getSmtpSettings($userId);
            if (!$smtpSettings) {
                throw new \Exception('SMTP settings not configured');
            }
            
            // Get test data from request
            $testData = $this->getTestData();
            
            // Process template
            $processed = $this->processor
                ->setVariables($testData)
                ->process($template['html_content']);
            
            // Configure email
            $emailConfig = [
                'to' => $testData['test_email'],
                'subject' => $testData['test_subject'],
                'from' => $smtpSettings['email'],
                'from_name' => $smtpSettings['name'],
                'html' => $processed,
                'css' => $template['css_content'],
                'attachments' => $testData['attachments'] ?? []
            ];
            
            // Send test email
            $result = $this->emailSender
                ->setSmtpConfig($smtpSettings)
                ->send($emailConfig);
            
            return [
                'success' => true,
                'message' => 'Test email sent successfully'
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function getTestData() {
        // Validate and sanitize input
        $testEmail = filter_input(INPUT_POST, 'test_email', FILTER_VALIDATE_EMAIL);
        if (!$testEmail) {
            throw new \Exception('Invalid test email address');
        }
        
        return [
            'test_email' => $testEmail,
            'test_subject' => filter_input(INPUT_POST, 'test_subject', FILTER_SANITIZE_STRING) ?? 'Test Email',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => $testEmail,
            'company_name' => 'Test Company',
            'unsubscribe_url' => '#test-unsubscribe',
            // Add more test data as needed
        ];
    }
} 