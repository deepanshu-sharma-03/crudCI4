<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

// JWT classes
use Firebase\JWT\JWT;

use Firebase\JWT\Key;

class JWTFilter implements FilterInterface
{
    // RUNS BEFORE REQUEST
    public function before(
        RequestInterface $request,
        $arguments = null
    ) {

        helper('cookie');

        // GET TOKEN FROM COOKIE
        $token =
            $request->getCookie(
                'token'
            );

        // TOKEN MISSING
        if (!$token) {

            return redirect()->to('/login');
        }

        try {

            // JWT SECRET
            $key =
                getenv(
                    'JWT_SECRET_KEY'
                );

            // VERIFY TOKEN
            $decoded =
                JWT::decode(

                    $token,

                    new Key(
                        $key,
                        'HS256'
                    )
                );
        }

        // INVALID TOKEN
        catch (\Exception $e) {

            return service('response')

                ->setJSON([

                    "status" => false,

                    "message" =>
                    $e->getMessage()
                ])

                ->setStatusCode(401);
        }
    }

    // RUNS AFTER REQUEST
    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {}
}
