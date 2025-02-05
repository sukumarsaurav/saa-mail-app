<?php

namespace Database\Seeders;

use App\Models\Template;

class AdditionalTemplatesSeeder {
    private $templates = [
        [
            'name' => 'Product Announcement',
            'html_content' => '
                <div class="announcement">
                    <header>
                        <img src="{{company_logo}}" alt="{{company_name}}" class="logo">
                        <h1>{{product_name}} is here!</h1>
                    </header>
                    <main>
                        <div class="product-image">
                            <img src="{{product_image}}" alt="{{product_name}}">
                        </div>
                        <div class="product-details">
                            <h2>Introducing {{product_name}}</h2>
                            <p>{{product_description}}</p>
                            <div class="price">{{formatCurrency(product_price)}}</div>
                            <a href="{{product_url}}" class="cta-button">Learn More</a>
                        </div>
                    </main>
                </div>
            ',
            'css_content' => '
                .announcement {
                    max-width: 600px;
                    margin: 0 auto;
                    font-family: Arial, sans-serif;
                    background: #ffffff;
                }
                .logo { max-width: 150px; }
                .product-image img { width: 100%; }
                .price {
                    font-size: 24px;
                    color: #2563eb;
                    margin: 20px 0;
                }
                .cta-button {
                    display: inline-block;
                    padding: 15px 30px;
                    background: #2563eb;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    transition: background 0.3s;
                }
            '
        ],
        [
            'name' => 'Event Invitation',
            'html_content' => '
                <div class="invitation">
                    <div class="header">
                        <h1>You\'re Invited!</h1>
                        <h2>{{event_name}}</h2>
                    </div>
                    <div class="details">
                        <p class="date">{{formatDate(event_date, "F j, Y")}}</p>
                        <p class="time">{{event_time}}</p>
                        <p class="location">{{event_location}}</p>
                    </div>
                    <div class="description">
                        {{event_description}}
                    </div>
                    <div class="rsvp">
                        <a href="{{rsvp_url}}" class="rsvp-button">RSVP Now</a>
                    </div>
                    <div class="footer">
                        <p>Add to calendar: 
                           <a href="{{calendar_google}}">Google</a> | 
                           <a href="{{calendar_outlook}}">Outlook</a>
                        </p>
                    </div>
                </div>
            ',
            'css_content' => '
                .invitation {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 40px;
                    background: #f8f9fa;
                    font-family: Arial, sans-serif;
                }
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                }
                .details {
                    text-align: center;
                    margin: 30px 0;
                    font-size: 18px;
                }
                .rsvp-button {
                    display: inline-block;
                    padding: 15px 30px;
                    background: #2563eb;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                    margin: 20px 0;
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