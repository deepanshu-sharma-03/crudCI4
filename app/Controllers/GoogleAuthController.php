<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use Google\Client;
use Firebase\JWT\JWT;
use App\Services\JwtService;

class GoogleAuthController extends Controller
{
    public function googleLogin()
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope("email");
        $client->addScope("profile");
        return redirect()->to(
            $client->createAuthUrl()
        );
    }

    public function googleCallback()
    {
        $client = new \Google\Client();

        $client->setClientId(
            env('GOOGLE_CLIENT_ID')
        );

        $client->setClientSecret(
            env('GOOGLE_CLIENT_SECRET')
        );

        $client->setRedirectUri(
            env('GOOGLE_REDIRECT_URI')
        );

        // GET GOOGLE CODE
        $code =
            $this->request->getGet('code');

        // CODE MISSING
        if (!$code) {

            return redirect()->to(
                base_url('login')
            );
        }

        // FETCH ACCESS TOKEN
        $token =
            $client->fetchAccessTokenWithAuthCode(
                $code
            );

        // TOKEN ERROR
        if (isset($token['error'])) {

            return redirect()->to(
                base_url('login')
            );
        }

        // SET ACCESS TOKEN
        $client->setAccessToken(
            $token
        );

        // GOOGLE USER SERVICE
        $googleService =
            new \Google\Service\Oauth2(
                $client
            );

        // GET USER INFO
        $googleUser =
            $googleService
            ->userinfo
            ->get();

        $email =
            (string)$googleUser->email;

        $name =
            (string)$googleUser->name;

        // USER MODEL
        $model =
            new UserModel();

        // CHECK USER
        $user =
            $model
            ->where('email', $email)
            ->first();

        // INSERT NEW USER
        if (!$user) {

            $insertData = [

                'name' => $name,

                'email' => $email,

                'mobile_number' => 'google',

                // HASH PASSWORD
                'password' => password_hash(
                    'google',
                    PASSWORD_DEFAULT
                ),

                'role' => 'user'
            ];

            $insertId =
                $model->insert(
                    $insertData
                );

            // email services
            $emailService = \config\Services::email();
            //load email view
            $message = view('registration_mail', ['name' => $insertData['name']]);

            // email details
            $emailService->setTo($email);
            $emailService->setSubject('Registration Successful');
            $emailService->setMessage($message);
            $emailService->send();
            $user =
                $model->find(
                    $insertId
                );
        }

        // GENERATE JWT
        $jwtToken =
            JwtService::generateToken(
                $user
            );

        // REDIRECT URL
        $redirect =
            $user['role'] == 'admin'
            ? base_url('admin')
            : base_url('user');

        // RESPONSE

        $response =
            redirect()->to(
                $redirect
            );

        $response->setCookie(
            'token',
            $jwtToken,
            3600
        );

        return $response;
        return $response;
    }
}
