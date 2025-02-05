<?php

namespace App\Utils;

class TemplateFunctions {
    public static function register(TemplateProcessor $processor) {
        // Array manipulation
        $processor->registerFunction('join', function($array, $separator = ', ') {
            return is_array($array) ? implode($separator, $array) : $array;
        });
        
        // Text formatting
        $processor->registerFunction('truncate', function($text, $length = 100) {
            return strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
        });
        
        $processor->registerFunction('nl2br', function($text) {
            return nl2br($text);
        });
        
        // Date/Time
        $processor->registerFunction('timeAgo', function($date) {
            $timestamp = strtotime($date);
            $diff = time() - $timestamp;
            
            if ($diff < 60) return 'just now';
            if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
            if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
            return floor($diff / 86400) . ' days ago';
        });
        
        // Number formatting
        $processor->registerFunction('number', function($number, $decimals = 0) {
            return number_format($number, $decimals);
        });
        
        // URL/Image handling
        $processor->registerFunction('imageUrl', function($path) {
            return rtrim($_ENV['APP_URL'], '/') . '/assets/images/' . ltrim($path, '/');
        });
        
        // Conditional content
        $processor->registerFunction('switch', function($value, ...$cases) {
            $default = array_pop($cases);
            $pairs = array_chunk($cases, 2);
            
            foreach ($pairs as $pair) {
                if ($pair[0] == $value) return $pair[1];
            }
            
            return $default;
        });
        
        // List formatting
        $processor->registerFunction('bulletList', function(...$items) {
            return '<ul>' . implode('', array_map(function($item) {
                return "<li>{$item}</li>";
            }, $items)) . '</ul>';
        });
        
        // Social media
        $processor->registerFunction('socialLink', function($platform, $username) {
            $urls = [
                'twitter' => 'https://twitter.com/',
                'facebook' => 'https://facebook.com/',
                'linkedin' => 'https://linkedin.com/in/',
                'instagram' => 'https://instagram.com/'
            ];
            
            return $urls[$platform] ?? '' . $username;
        });
    }
} 