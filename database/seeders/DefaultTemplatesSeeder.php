<?php

namespace Database\Seeders;

use App\Models\Template;

class DefaultTemplatesSeeder {
    private $templates = [
        [
            'name' => 'Welcome Email',
            'html_content' => '
                <div style="max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif;">
                    <h1>Welcome to Our Service!</h1>
                    <p>Dear {{first_name}},</p>
                    <p>Thank you for joining us. We\'re excited to have you on board!</p>
                    <div class="cta-button">
                        <a href="{{action_url}}">Get Started</a>
                    </div>
                </div>
            ',
            'css_content' => '
                h1 { color: #2563eb; }
                .cta-button {
                    margin: 20px 0;
                    text-align: center;
                }
                .cta-button a {
                    display: inline-block;
                    padding: 12px 24px;
                    background-color: #2563eb;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                }
            '
        ],
        [
            'name' => 'Newsletter Template',
            'html_content' => '
                <div class="newsletter">
                    <header>
                        <img src="{{logo_url}}" alt="Logo">
                        <h1>{{newsletter_title}}</h1>
                    </header>
                    <main>
                        {{content}}
                    </main>
                    <footer>
                        <p>To unsubscribe, <a href="{{unsubscribe_url}}">click here</a></p>
                    </footer>
                </div>
            ',
            'css_content' => '
                .newsletter {
                    max-width: 600px;
                    margin: 0 auto;
                    font-family: Arial, sans-serif;
                }
                header {
                    text-align: center;
                    padding: 20px;
                }
                main {
                    padding: 20px;
                }
                footer {
                    text-align: center;
                    padding: 20px;
                    font-size: 12px;
                }
            '
        ]
    ];

    public function seed() {
        $template = new Template();
        
        foreach ($this->templates as $templateData) {
            $templateData['is_default'] = 1;
            $templateData['js_content'] = '';
            $template->createTemplate(0, $templateData);
        }
    }
} 