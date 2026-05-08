<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(
        RequestInterface $request,
        $arguments = null
    )
     {
        $session = session();
        // ❌ Not logged in
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }
        // 🔐 Role check
        if (!empty($arguments)) {
            $role = $session->get('role');
            // admin/user mismatch
            if ($role !== $arguments[0]) {
                // redirect according to role
                if ($role == 'admin') {
                    return redirect()->to(base_url('admin'));
                } else {
                    return redirect()->to(base_url('user'));
                }
            }
        }
    }
    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        // after filter
    }
}