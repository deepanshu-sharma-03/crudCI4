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

    public function loginUser()
    {
        $model = new UserModel();
        $email = $this->request->getPost('email');
        $mobile_number = $this->request->getPost('mobile_number');
        $password  = $this->request->getPost('password');
        $user = $model->where('email',$email)->first();
        if($user){
            // passsword check 
            if($password==$user['password']){
                // session()->destroy();
                // create session 
                session()->set([
                    'id'=> $user['id'],
                    'name'=>$user['name'],
                    'email'=>$user['email'],
                    'mobile_number'=>$user['mobile_number'],
                    'role'=>$user['role'],
                    'isLoggedIn'=> 'true',
                ]);
                if($user['role']=='admin'){
                    return redirect()->to('/admin');
                }else{
                    return redirect()->to('/user');
                }
            }else{
                return redirect()->back()->with('error','wrong Password');
            }
        }else{
            return redirect()->back()->with('error','email not found');
        }
    }

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
            'mobile'=> 'google',
            'password' => '',
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
    // REDIRECT
    if($user['role'] == 'admin'){
        return redirect()->to('/admin');
    }else{
        return redirect()->to('/user');
    }
}
}
?>