<?php

namespace App\Services;
class OtpService
{
    // GENERATE OTP
    public static function generateOtp()
    {
        return rand(100000,999999);
    }

    // SEND OTP MAIL
    public static function sendOtpMail(
        $email,
        $otp
    )
    {
        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setSubject(
            'Your Login OTP'
        );
        $emailService->setMessage("
            <h3>Your OTP is:</h3>
            <h1>$otp</h1>
            <p>Valid for 10 minutes</p>
        ");
        return $emailService->send();
    }
}