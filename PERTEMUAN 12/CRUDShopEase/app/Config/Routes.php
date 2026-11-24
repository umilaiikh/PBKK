<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Admin\ProductController;
use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\EmployeeController;

/** @var RouteCollection $routes */
$routes->get('/', 'StoreController::index');
$routes->get('products', 'StoreController::index');
$routes->get('products/(:num)', 'StoreController::detail/$1');

$routes->get('register', 'AuthController::register');
$routes->post('register', 'AuthController::saveRegister');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->post('logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
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

// Halaman khusus administrator.
$routes->group('admin', ['filter' => 'role:admin'], static function ($routes) {
    $routes->get('/', 'AdminController::dashboard');
    $routes->get('orders', 'AdminController::orders');
    $routes->post('orders/payment/(:num)/(:segment)', 'AdminController::verifyPayment/$1/$2');
    $routes->post('orders/status/(:num)', 'AdminController::updateStatus/$1');
    $routes->get('employees', [EmployeeController::class, 'index']);
    $routes->get('employees/create', [EmployeeController::class, 'create']);
    $routes->post('employees/store', [EmployeeController::class, 'store']);
    $routes->get('employees/edit/(:num)', [EmployeeController::class, 'edit']);
    $routes->post('employees/update/(:num)', [EmployeeController::class, 'update']);
    $routes->post('employees/delete/(:num)', [EmployeeController::class, 'delete']);
});

// Product Management: dapat diakses Admin dan Operator.
$routes->group('admin', ['filter' => 'role:admin,operator'], static function ($routes) {
    $routes->get('products', [ProductController::class, 'index']);
    $routes->get('products/create', [ProductController::class, 'create']);
    $routes->post('products/store', [ProductController::class, 'store']);
    $routes->get('products/edit/(:num)', [ProductController::class, 'edit']);
    $routes->post('products/update/(:num)', [ProductController::class, 'update']);
    $routes->post('products/delete/(:num)', [ProductController::class, 'delete']);
    $routes->post('products/images/delete/(:num)', [ProductController::class, 'deleteImage']);
    $routes->get('categories', [CategoryController::class, 'index']);
    $routes->get('categories/create', [CategoryController::class, 'create']);
    $routes->post('categories/store', [CategoryController::class, 'store']);
    $routes->get('categories/edit/(:num)', [CategoryController::class, 'edit']);
    $routes->post('categories/update/(:num)', [CategoryController::class, 'update']);
    $routes->post('categories/delete/(:num)', [CategoryController::class, 'delete']);
});

$routes->group('operator', ['filter' => 'role:operator,admin'], static function ($routes) {
    $routes->get('orders', 'AdminController::orders');
    $routes->post('orders/status/(:num)', 'AdminController::updateStatus/$1');
});
