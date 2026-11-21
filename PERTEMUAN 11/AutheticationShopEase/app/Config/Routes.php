<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'StoreController::index');
$routes->get('products', 'StoreController::index');
$routes->get('products/(:num)', 'StoreController::detail/$1');

$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::saveRegister');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->post('logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], static function($routes) {
    $routes->get('cart', 'CartController::index');
    $routes->post('cart/add/(:num)', 'CartController::add/$1');
    $routes->post('cart/update/(:num)', 'CartController::update/$1');
    $routes->post('cart/remove/(:num)', 'CartController::remove/$1');
    $routes->get('checkout', 'CheckoutController::index');
    $routes->post('checkout', 'CheckoutController::place');
    $routes->get('orders', 'OrderController::index');
    $routes->get('orders/(:num)', 'OrderController::detail/$1');
    $routes->post('orders/(:num)/payment', 'OrderController::uploadPayment/$1');
});

$routes->group('admin', ['filter' => 'role:admin'], static function($routes) {
    $routes->get('/', 'AdminController::dashboard');
    $routes->get('categories', 'AdminController::categories');
    $routes->post('categories/save', 'AdminController::saveCategory');
    $routes->post('categories/delete/(:num)', 'AdminController::deleteCategory/$1');
    $routes->get('products', 'AdminController::products');
    $routes->get('products/new', 'AdminController::productForm');
    $routes->get('products/edit/(:num)', 'AdminController::productForm/$1');
    $routes->post('products/save', 'AdminController::saveProduct');
    $routes->post('products/delete/(:num)', 'AdminController::deleteProduct/$1');
    $routes->get('orders', 'AdminController::orders');
    $routes->post('orders/payment/(:num)/(:segment)', 'AdminController::verifyPayment/$1/$2');
    $routes->post('orders/status/(:num)', 'AdminController::updateStatus/$1');
});

$routes->group('operator', ['filter' => 'role:operator,admin'], static function($routes) {
    $routes->get('orders', 'AdminController::orders');
    $routes->post('orders/status/(:num)', 'AdminController::updateStatus/$1');
});
