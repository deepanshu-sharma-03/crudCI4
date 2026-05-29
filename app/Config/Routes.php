<?php

use CodeIgniter\Router\RouteCollection;


/**
 * @var RouteCollection $routes
 */

$routes->get('login', 'AuthController::login');


$routes->get('register', 'RegisterController::register');
$routes->post('registerUser', 'RegisterController::registerUser');

$routes->get('logout', 'AuthController::logout');

$routes->get('google-login', 'GoogleAuthController::googleLogin');
$routes->get('google-callback', 'GoogleAuthController::googleCallback');

$routes->post('send-otp', 'OtpController::sendOtp');
$routes->post('verify-otp', 'OtpController::verifyOtp');


$routes->group('', ['filter' => 'jwt'], function ($routes) {
    $routes->group('', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('admin', 'Admin\AdminController::admin');

        $routes->get('get-users/(:num)', 'Admin\AdminController::getUsers/$1');
        $routes->post('add-user', 'Admin\AdminController::addUser');
        $routes->get('get-user/(:num)', 'Admin\AdminController::getUser/$1');
        $routes->post('update/(:num)', 'Admin\AdminController::updateUser/$1');
        $routes->get('delete/(:num)', 'Admin\AdminController::delete/$1');


        $routes->get('/products', 'Products\ProductController::getProducts');
        $routes->get('/add-product', 'Products\ProductController::addProduct');
        $routes->post('/save-product', 'Products\ProductController::saveProduct');

        $routes->get('/edit/(:num)', 'Products\ProductController::editProduct/$1');
        $routes->post('update-product/(:num)', 'Products\ProductController::updateProduct/$1');
        $routes->get('delete-product/(:num)', 'Products\ProductController::deleteProduct/$1');
        $routes->post('product/toggle-status', 'Products\ProductController::toggleStatus');
        // notifications

        $routes->get('notification', 'Notification\NotificationController::nfPage');
        $routes->post('notification/update-status', 'Notification\NotificationController::update');
        $routes->post('notification/create-notification', 'Notification\NotificationController::save');
    });
    $routes->group('', ['filter' => ['role:user']], function ($routes) {
        $routes->get('user', 'UserController::user');
        $routes->get('get-user-profile', 'UserController::getProfile');
        $routes->post('update-user-profile', 'UserController::updateProfile');

        // NOTIFICATION
        $routes->post('/user/notification-status', 'UserController::updateNotificationStatus');



        $routes->post('/add-cart', 'Cart\CartController::addToCart');
        $routes->get('/cart', 'Cart\CartController::showCart');
        $routes->post('/update-cart', 'Cart\CartController::updateCart');
        $routes->post('/cart-remove', 'Cart\CartController::deleteFromCart');
        $routes->get('user/view-products', 'Products\ProductController::viewProducts');
        $routes->get('/validate-checkout', 'Checkout\CheckoutController::validateCheckout');
        $routes->post('/checkout', 'Checkout\CheckoutController::checkout');
        // PAYMENT 
        $routes->post('checkout/placeorder', 'Payment\PaymentController::placeOrder');
        // RAZORPAY 
        $routes->post('checkout/startPayment', 'Payment\PaymentController::startPayment');
        $routes->post('/checkout/successPayment', 'Payment\PaymentController::successPayment');
        $routes->post('/checkout/failedPayment', 'Payment\PaymentController::failedPayment');

        //ORDER SECTION
        $routes->get('user/orders', 'Orders\OrderController::showOrder');
    });
});
