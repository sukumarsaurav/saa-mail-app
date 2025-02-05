<?php

namespace App\Utils;

class TemplateProcessor {
    private $variables = [];
    private $customFunctions = [];

    public function __construct() {
        $this->registerDefaultFunctions();
    }

    public function setVariables(array $variables) {
        $this->variables = array_merge($this->variables, $variables);
        return $this;
    }

    public function process($content) {
        // Process custom functions first
        $content = preg_replace_callback(
            '/\{\{(.*?)\}\}/',
            [$this, 'processFunction'],
            $content
        );

        // Process regular variables
        $content = preg_replace_callback(
            '/\{\{(.*?)\}\}/',
            [$this, 'replaceVariable'],
            $content
        );

        return $content;
    }

    private function replaceVariable($matches) {
        $key = trim($matches[1]);
        return $this->variables[$key] ?? '';
    }

    private function processFunction($matches) {
        $expression = trim($matches[1]);
        
        // Check if it's a function call
        if (preg_match('/^([a-zA-Z_]+)\((.*?)\)$/', $expression, $funcMatches)) {
            $funcName = $funcMatches[1];
            $args = array_map('trim', explode(',', $funcMatches[2]));
            
            if (isset($this->customFunctions[$funcName])) {
                return call_user_func_array($this->customFunctions[$funcName], $args);
            }
        }
        
        return $matches[0];
    }

    private function registerDefaultFunctions() {
        // Date formatting
        $this->customFunctions['formatDate'] = function($date, $format = 'Y-m-d') {
            return date($format, strtotime($date));
        };

        // Currency formatting
        $this->customFunctions['formatCurrency'] = function($amount, $currency = 'USD') {
            $formatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
            return $formatter->formatCurrency($amount, $currency);
        };

        // String manipulation
        $this->customFunctions['uppercase'] = function($str) {
            return strtoupper($str);
        };

        $this->customFunctions['lowercase'] = function($str) {
            return strtolower($str);
        };

        // Conditional content
        $this->customFunctions['if'] = function($condition, $true, $false = '') {
            return $condition ? $true : $false;
        };

        // URL generation
        $this->customFunctions['url'] = function($path) {
            return rtrim($_ENV['APP_URL'], '/') . '/' . ltrim($path, '/');
        };
    }

    public function registerFunction($name, callable $callback) {
        $this->customFunctions[$name] = $callback;
        return $this;
    }

    public function previewReplace($content) {
        return preg_replace_callback(
            '/\{\{(.*?)\}\}/',
            function($matches) {
                return '<span class="template-variable">' . $matches[0] . '</span>';
            },
            $content
        );
    }
} 