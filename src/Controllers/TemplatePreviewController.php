<?php

namespace App\Controllers;

use App\Models\Template;
use App\Utils\TemplateProcessor;
use App\Utils\VariableValidator;

class TemplatePreviewController {
    private $template;
    private $processor;
    private $validator;
    
    public function __construct() {
        $this->template = new Template();
        $this->processor = new TemplateProcessor();
        $this->validator = new VariableValidator();
    }
    
    public function preview($id) {
        try {
            $userId = $_SESSION['user_id'];
            $template = $this->template->getTemplate($id, $userId);
            
            if (!$template) {
                throw new \Exception('Template not found');
            }
            
            // Get sample data
            $sampleData = $this->getSampleData($template);
            
            // Process template with sample data
            $processed = $this->processor
                ->setVariables($sampleData)
                ->process($template['html_content']);
            
            // Return preview with highlighting
            return [
                'success' => true,
                'html' => $processed,
                'css' => $template['css_content'],
                'js' => $template['js_content'],
                'variables' => $this->extractVariables($template['html_content'])
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function getSampleData($template) {
        // Extract variables from template
        $variables = $this->extractVariables($template['html_content']);
        
        // Generate sample data for each variable
        $sampleData = [];
        foreach ($variables as $var) {
            $sampleData[$var] = $this->generateSampleValue($var);
        }
        
        return $sampleData;
    }
    
    private function generateSampleValue($variable) {
        $samples = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'company_name' => 'Acme Inc.',
            'product_name' => 'Amazing Product',
            'product_price' => '99.99',
            'event_date' => date('Y-m-d', strtotime('+1 week')),
            'event_time' => '14:00',
            'event_location' => 'Virtual Meeting',
            'unsubscribe_url' => '#unsubscribe'
        ];
        
        return $samples[$variable] ?? "{{$variable}}";
    }
    
    private function extractVariables($content) {
        preg_match_all('/\{\{([^}]+)\}\}/', $content, $matches);
        return array_unique($matches[1]);
    }
} 