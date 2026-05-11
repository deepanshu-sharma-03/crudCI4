<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UserModel;

use Google\Client;
use Google\Service\Oauth2;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }
    public function register()
    {
        return view('register');
    }
    public function registerUser()
    {
        $model = new UserModel();
        $data = [
            'name'=>$this->request->getPost('name'),
            'email'=>$this->request->getPost('email'),
            'mobile_number'=>$this->request->getPost('mobile_number'),
            'password'=>$this->request->getPost('password'),
            'role'=>'user'
        ];
        $model->insert($data);
        return redirect()->to('/login');
    }

    // public function loginUser()
    // {
    //     $model = new UserModel();
    //     $email = $this->request->getPost('email');
    //     $mobile_number = $this->request->getPost('mobile_number');
    //     $password  = $this->request->getPost('password');
    //     $user = $model->where('email',$email)->first();
    //     if($user){
    //         // passsword check 
    //         if($password==$user['password']){
    //             // session()->destroy();
    //             // create session 
    //             // session()->set([
    //             //     'id'=> $user['id'],
    //             //     'name'=>$user['name'],
    //             //     'email'=>$user['email'],
    //             //     'mobile_number'=>$user['mobile_number'],
    //             //     'role'=>$user['role'],
    //             //     'isLoggedIn'=> 'true',
    //             // ]);
    //             if($user['role']=='admin'){
    //                 return redirect()->to('/admin');
    //             }else{
    //                 return redirect()->to('/user');
    //             }
    //         }else{
    //             return redirect()->back()->with('error','wrong Password');
    //         }
    //     }else{
    //         return redirect()->back()->with('error','email not found');
    //     }
    // }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function googleLogin()
{
    $client = new Client();
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri(
        env('GOOGLE_REDIRECT_URI')
    );
    $client->addScope("email");
    $client->addScope("profile");
    return redirect()->to(
        $client->createAuthUrl()
    );
}

public function googleCallback()
{
    $client = new \Google\Client();
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri(
        env('GOOGLE_REDIRECT_URI')
    );
    $code = $this->request->getGet('code');
    if(!$code){
        die("Google code missing");
    }
    $token = $client->fetchAccessTokenWithAuthCode($code);
    $client->setAccessToken($token);
    $googleService = new \Google\Service\Oauth2($client);
    $googleUser = $googleService->userinfo->get();
    $email = (string)$googleUser->email;
    $name = (string)$googleUser->name;
    $model = new UserModel();

    // CHECK USER
    $user = $model->where('email', $email)->first();
    // INSERT USER
    if(!$user){
        $data = [
            'name' => $name,
            'email' => $email,
            'mobile_number'=> 'google',
            'password' => 'loginGoogle',
            'role' => 'user'
        ];
        // INSERT
        $insertId = $model->insert($data);
        // FAILED
        if(!$insertId){
            print_r($model->errors());
            die();
        }
        // FETCH USING INSERT ID
        $user = $model->find($insertId);
    }
    // STILL NULL
    if(!$user){
        die("User not found");
    }

    // SESSION
    session()->set([
        'id' => $user['id'],
        'name' => $user['name'],
        'mobile_number' => $user['mobile_number'],
        'email' => $user['email'],
        'role' => $user['role'],
        'isLoggedIn' => true
    ]);
    // print_r(session()->get());
    // die();
    // REDIRECT
    if($user['role'] == 'admin'){
        return redirect()->to('/admin');
    }else{
        return redirect()->to('/user');
    }
}
    // SEND OTP
    public function sendOtp()
    {
        $model = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        // check user
        $user = $model->where('email', $email)->where('password', $password)->first();
             if(!$user){
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid Credentials'
            ]);
        }

        // generate otp
        $otp = rand(100000,999999);

        // store otp
        session()->set('otp', $otp);

        // store otp time
        session()->set('otp_time', time());

        // temporary user
        session()->set('temp_user', $user);

        /*
        -----------------------------------
        SEND OTP TO GMAIL
        -----------------------------------
        */

        $emailService = \Config\Services::email();
        $emailService->setTo($email);
        $emailService->setSubject('Your Login OTP');
        $emailService->setMessage("
            <h3>Your OTP is:</h3>
            <h1>$otp</h1>
            <p>Valid for 10 minutes only.</p>
        ");

        // send email
        if($emailService->send()){
            return $this->response->setJSON([
                'status' => true
            ]);
        }else{
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Unable to send OTP'
            ]);
        }
    }

     // VERIFY OTP
    public function verifyOtp()
    {
        $model = new UserModel();
        $userOtp = $this->request->getPost('otp');
        $sessionOtp = session()->get('otp');
        $otpTime = session()->get('otp_time');
        $currentTime = time();
        // time difference
        $diff = $currentTime - $otpTime;

        // 10 minutes = 600 sec
        if($diff > 600){

            session()->remove('otp');
            session()->remove('otp_time');
            session()->remove('temp_user');

            return $this->response->setJSON([
                'status' => false,
                'message' => 'OTP Expired'
            ]);
        }

        // verify otp
        if($userOtp == $sessionOtp){
            $tmp_user = session()->get('temp_user');
            $user = $model->find($tmp_user['id']);
            
            // login session
            session()->set([ 
            'id' => $user['id'],
            'name' => $user['name'],
            'email'=>$user['email'],
            'password'=>$user['password'],
            'mobile_number'=> $user['mobile_number'],
            'role'=>$user['role'],
            'isLoggedIn' => true
            ]);
            

            // clear otp data
            session()->remove('otp');
            session()->remove('otp_time');
            session()->remove('temp_user');

            // redirect by role
            $redirect = $user['role'] == 'admin'
                ? base_url('admin')
                : base_url('user');
                return $this->response->setJSON([
                'status' => true,
                'redirect' => $redirect
            ]);

        }else{

            return $this->response->setJSON([
                'status' => false,
                'message' => 'Invalid OTP'
            ]);
        }
    }
}

?>