<?php

namespace App\Utils;

class VariableValidator {
    private $requiredVariables = [];
    private $optionalVariables = [];
    private $customValidators = [];

    public function addRequired($variables) {
        $this->requiredVariables = array_merge($this->requiredVariables, (array)$variables);
        return $this;
    }

    public function addOptional($variables) {
        $this->optionalVariables = array_merge($this->optionalVariables, (array)$variables);
        return $this;
    }

    public function addValidator($variable, callable $validator) {
        $this->customValidators[$variable] = $validator;
        return $this;
    }

    public function validate($template, $variables) {
        $errors = [];
        $matches = [];
        
        // Find all variables in template
        preg_match_all('/\{\{(.*?)\}\}/', $template, $matches);
        $templateVars = array_map('trim', $matches[1]);
        
        // Check required variables
        foreach ($this->requiredVariables as $var) {
            if (!isset($variables[$var]) || empty($variables[$var])) {
                $errors[] = "Required variable '{$var}' is missing or empty";
            }
        }
        
        // Validate provided variables
        foreach ($variables as $key => $value) {
            if (isset($this->customValidators[$key])) {
                $validator = $this->customValidators[$key];
                $result = $validator($value);
                
                if ($result !== true) {
                    $errors[] = is_string($result) ? $result : "Invalid value for '{$key}'";
                }
            }
        }
        
        // Check for undefined variables
        $allowedVars = array_merge($this->requiredVariables, $this->optionalVariables);
        foreach ($templateVars as $var) {
            if (!in_array($var, $allowedVars) && !preg_match('/^[a-zA-Z_]+\(.*\)$/', $var)) {
                $errors[] = "Undefined variable '{$var}' used in template";
            }
        }
        
        return [
            'isValid' => empty($errors),
            'errors' => $errors
        ];
    }
} 