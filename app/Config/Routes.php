<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/admin', 'UserController::admin',['filter'=>'auth:admin']);
$routes->get('/user','UserController::user',['filter'=>'auth:user']);

$routes->get('get-users/(:num)','UserController::getUsers/$1');
$routes->post('store','UserController::store');
$routes->get('get-user/(:num)','UserController::getUser/$1');
$routes->post('update/(:num)','UserController::update/$1');
$routes->get('delete/(:num)','UserController::delete/$1');


$routes->get('login','AuthController::login');
$routes->post('loginUser','AuthController::loginUser');

$routes->get('register','AuthController::register');
$routes->post('registerUser','AuthController::registerUser');

$routes->get('logout','AuthController::logout');

$routes->get('google-login','AuthController::googleLogin');
$routes->get('google-callback','AuthController::googleCallback');

$routes->post('send-otp','AuthController::sendOtp');
$routes->post('verify-otp','AuthController::verifyOtp');


$routes->get('get-profile','UserController::getProfile');
$routes->post('update-profile','UserController::updateProfile');