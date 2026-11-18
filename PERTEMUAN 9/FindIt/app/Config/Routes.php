<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attempt');
$routes->get('/logout', 'Auth::logout');

$routes->group('items', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Item::index');
    $routes->get('create', 'Item::create');
    $routes->post('store', 'Item::store');
    $routes->get('edit/(:num)', 'Item::edit/$1');
    $routes->post('update/(:num)', 'Item::update/$1');
    $routes->post('delete/(:num)', 'Item::delete/$1');
}); 