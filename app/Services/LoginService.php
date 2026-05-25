<?php

namespace App\Services;

use App\Models\UserModel;

class LoginService
{
    protected $userModel;
    protected $otpService;
    protected $jwtService;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->otpService =  new OtpService();
        $this->jwtService =  new JwtService();
    }
    // REGISTER USER
    public function register($request)
    {
        $email = $request->getPost('email');
        $existingUser = $this->userModel->where('email', $email)->first();
        if ($existingUser) {
            return redirect()->back()->with('error', 'Email already exists');
        }
        $data = [
            'name' => $request->getPost('name'),
            'email' => $email,
            'mobile_number' => $request->getPost('mobile_number'),
            'password' =>  $request->getPost('password'),
            'role' => 'user'
        ];
        // email services
        $emailService = \config\Services::email();
        //load email view
        $message = view('registration_mail', ['name' => $data['name']]);

        // email details
        $emailService->setTo($email);
        $emailService->setSubject('Registration Successful');
        $emailService->setMessage($message);
        $emailService->send();
        $this->userModel->insert($data);
        return redirect()->to('/login');
    }

    // SEND OTP
    public function sendOtp($request)
    {
        $email = $request->getPost('email');
        $password = $request->getPost('password');
        $user = $this->userModel->where('email', $email)->first();
        if (!$user) {
            return response()->setJSON([
                'status' => false,
                'message' => 'Email not found'
            ]);
        }

        // VERIFY PASSWORD
        if ($password != $user['password']) {
            return response()->setJSON([
                'status' => false,
                'message' => 'Wrong Password'
            ]);
        }

        // GENERATE OTP
        $otp = $this->otpService->generateOtp();

        // STORE OTP
        session()->set([
            'otp' => $otp,
            'otp_time' => time(),
            'temp_user' => $user
        ]);

        // SEND MAIL
        $mail = $this->otpService->sendOtpMail($email, $otp);
        if ($mail) {
            return response()
                ->setJSON([
                    'status' => true
                ]);
        }
        return response()->setJSON([
            'status' => false,
            'message' => 'Unable to send OTP'
        ]);
    }

    // VERIFY OTP
    public function verifyOtp($request)
    {
        $userOtp = $request->getPost('otp');
        $sessionOtp = session()->get('otp');
        $otpTime = session()->get('otp_time');

        // OTP EXPIRE
        if (time() - $otpTime > 600) {
            session()->remove([
                'otp',
                'otp_time',
                'temp_user'
            ]);
            return response()->setJSON([
                'status' => false,
                'message' => 'OTP Expired'
            ]);
        }

        // INVALID OTP
        if ($userOtp != $sessionOtp) {
            return response()->setJSON([
                'status' => false,
                'message' => 'Invalid OTP'
            ]);
        }

        $user =  session()->get('temp_user');

        // GENERATE JWT
        $token = $this->jwtService->generateToken($user);

        // CLEAR SESSION
        session()->remove([
            'otp',
            'otp_time',
            'temp_user'
        ]);

        // REDIRECT
        $redirect = $user['role'] == 'admin' ? base_url('admin') : base_url('user');
        // STORE TOKEN IN COOKIE
        $response = response();

        $response->setCookie(
            'token',
            $token,
            3600
        );

        return $response->setJSON([
            'status'   => true,
            'redirect' => $redirect,
            'user'     => $user
        ]);
    }
}
