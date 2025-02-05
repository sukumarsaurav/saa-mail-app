<?php

namespace App\Auth;

use App\Models\User;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailAuth {
    private $user;
    private $mailer;

    public function __construct() {
        $this->user = new User();
        $this->mailer = new PHPMailer(true);
        
        // Configure PHPMailer
        $this->mailer->isSMTP();
        $this->mailer->Host = $_ENV['SMTP_HOST'];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $_ENV['SMTP_USERNAME'];
        $this->mailer->Password = $_ENV['SMTP_PASSWORD'];
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = $_ENV['SMTP_PORT'];
    }

    public function register(array $userData) {
        if ($this->user->findByEmail($userData['email'])) {
            throw new \Exception('Email already exists');
        }

        $verificationToken = bin2hex(random_bytes(32));
        $userData['verification_token'] = $verificationToken;
        
        if ($this->user->create($userData)) {
            $this->sendVerificationEmail($userData['email'], $verificationToken);
            return true;
        }
        
        return false;
    }

    public function login(string $email, string $password) {
        $user = $this->user->findByEmail($email);
        
        if (!$user) {
            throw new \Exception('User not found');
        }

        if (!password_verify($password, $user['password'])) {
            throw new \Exception('Invalid password');
        }

        if (!$user['is_verified']) {
            throw new \Exception('Email not verified');
        }

        return $user;
    }

    private function sendVerificationEmail(string $email, string $token) {
        try {
            $this->mailer->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            $this->mailer->addAddress($email);
            $this->mailer->isHTML(true);
            
            $verificationLink = $_ENV['APP_URL'] . "/verify-email?token=" . $token;
            
            $this->mailer->Subject = 'Verify Your Email Address';
            $this->mailer->Body = "
                <h1>Email Verification</h1>
                <p>Please click the link below to verify your email address:</p>
                <a href='{$verificationLink}'>{$verificationLink}</a>
            ";

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            throw new \Exception('Email could not be sent: ' . $this->mailer->ErrorInfo);
        }
    }

    public function verifyEmail(string $token) {
        return $this->user->verifyEmailToken($token);
    }

    public function requestPasswordReset(string $email) {
        $user = $this->user->findByEmail($email);
        
        if (!$user) {
            throw new \Exception('User not found');
        }

        $resetToken = bin2hex(random_bytes(32));
        $this->user->storeResetToken($email, $resetToken);
        
        // Send password reset email
        try {
            $this->mailer->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            $this->mailer->addAddress($email);
            $this->mailer->isHTML(true);
            
            $resetLink = $_ENV['APP_URL'] . "/reset-password?token=" . $resetToken;
            
            $this->mailer->Subject = 'Reset Your Password';
            $this->mailer->Body = "
                <h1>Password Reset</h1>
                <p>Click the link below to reset your password:</p>
                <a href='{$resetLink}'>{$resetLink}</a>
            ";

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            throw new \Exception('Reset email could not be sent: ' . $this->mailer->ErrorInfo);
        }
    }
}
