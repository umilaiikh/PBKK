<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('/items', 'Item::index');
$routes->get('/items/create', 'Item::create');
$routes->post('/items/store', 'Item::store');
$routes->get('/items/edit/(:num)', 'Item::edit/$1');
$routes->post('/items/update/(:num)', 'Item::update/$1');
$routes->post('/items/delete/(:num)', 'Item::delete/$1');