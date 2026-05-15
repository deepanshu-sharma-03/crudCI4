<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\AuthController;

use App\Controllers\RegisterController;

use App\Controllers\OtpController;

use App\Controllers\GoogleAuthController;

/**
 * @var RouteCollection $routes
 */

$routes->get('login', 'AuthController::login');
$routes->post('loginUser', 'AuthController::loginUser');

$routes->get('register', 'RegisterController::register');
$routes->post('registerUser', 'RegisterController::registerUser');

$routes->get('logout', 'AuthController::logout');

$routes->get('google-login', 'GoogleAuthController::googleLogin');
$routes->get('google-callback', 'GoogleAuthController::googleCallback');

$routes->post('send-otp', 'OtpController::sendOtp');
$routes->post('verify-otp', 'OtpController::verifyOtp');

//  $routes->get('admin','UserController::admin');
//  $routes->get('user','UserController::user');

$routes->group('', ['filter' => 'jwt'], function ($routes) {
    $routes->get('admin', 'UserController::admin');
    $routes->get('user', 'UserController::user');
    $routes->get('get-users/(:num)', 'UserController::getUsers/$1');
    $routes->post('store', 'UserController::store');
    $routes->get('get-user/(:num)', 'UserController::getUser/$1');
    $routes->post('update/(:num)', 'UserController::update/$1');
    $routes->get('delete/(:num)', 'UserController::delete/$1');
    $routes->get('get-profile', 'UserController::getProfile');
    $routes->post('update-profile', 'UserController::updateProfile');
});
$routes->get('/products', 'Products\ProductController::getProducts');
$routes->get('/add-product', 'Products\ProductController::addProduct');
$routes->post('/save-product', 'Products\ProductController::saveProduct');

$routes->get('/edit/(:num)', 'Products\ProductController::editProduct/$1');
$routes->post('update-product/(:num)', 'Products\ProductController::updateProduct/$1');
$routes->get('delete-product/(:num)', 'Products\ProductController::deleteProduct/$1');
$routes->post('product/toggle-status', 'Products\ProductController::toggleStatus');
$routes->get('user/view-products', 'Products\ProductController::viewProducts');
