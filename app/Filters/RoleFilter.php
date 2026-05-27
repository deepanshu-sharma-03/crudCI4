<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = $request->user;

        if (!$user) {
            return redirect()->to('/login');
        }

        $requiredRole = $arguments[0] ?? null;

        if ($requiredRole && $user->role !== $requiredRole) {

            // Admin trying to open user page
            if ($user->role === 'admin') {

                return redirect()
                    ->to('/admin')
                    ->with('error', 'Access Denied');
            }

            // User trying to open admin page
            if ($user->role === 'user') {

                return redirect()
                    ->to('/user')
                    ->with('error', 'Access Denied');
            }
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {}
}
