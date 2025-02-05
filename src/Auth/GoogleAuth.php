<?php

namespace App\Auth;

use Google_Client;
use App\Models\User;

class GoogleAuth {
    private $client;
    private $user;

    public function __construct() {
        $this->client = new Google_Client();
        $this->client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $this->client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $this->client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
        $this->client->addScope('email');
        $this->client->addScope('profile');
        
        $this->user = new User();
    }

    public function getAuthUrl() {
        return $this->client->createAuthUrl();
    }

    public function handleCallback(string $code) {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($code);
            $this->client->setAccessToken($token);
            
            $google_oauth = new \Google_Service_Oauth2($this->client);
            $google_account_info = $google_oauth->userinfo->get();
            
            $email = $google_account_info->email;
            $name = $google_account_info->name;
            
            $existingUser = $this->user->findByEmail($email);
            
            if (!$existingUser) {
                $userData = [
                    'email' => $email,
                    'name' => $name,
                    'password' => bin2hex(random_bytes(32)), // Random password for Google users
                    'google_oauth_token' => json_encode($token)
                ];
                
                $this->user->createGoogleUser($userData);
                return $this->user->findByEmail($email);
            }
            
            $this->user->updateGoogleToken($email, json_encode($token));
            return $existingUser;
            
        } catch (\Exception $e) {
            throw new \Exception('Google authentication failed: ' . $e->getMessage());
        }
    }
}
