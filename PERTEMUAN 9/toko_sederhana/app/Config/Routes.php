<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Toko');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->get('/', 'Toko::index');
$routes->get('barang/(:num)', 'Toko::detail/$1');

$routes->get('keranjang', 'Keranjang::index');
$routes->post('keranjang/tambah/(:num)', 'Keranjang::tambah/$1');
$routes->post('keranjang/update', 'Keranjang::update');
$routes->post('keranjang/hapus/(:num)', 'Keranjang::hapus/$1');

$routes->get('checkout', 'Checkout::index');
$routes->post('checkout/proses', 'Checkout::proses');
$routes->get('pesanan/(:segment)', 'Checkout::detail/$1');

$routes->get('admin/login', 'Admin\Auth::login');
$routes->post('admin/login', 'Admin\Auth::attempt');
$routes->get('admin/logout', 'Admin\Auth::logout');

$routes->group('admin', ['filter' => 'adminauth'], static function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('barang', 'Admin\Barang::index');
    $routes->get('barang/tambah', 'Admin\Barang::tambah');
    $routes->post('barang/simpan', 'Admin\Barang::simpan');
    $routes->get('barang/edit/(:num)', 'Admin\Barang::edit/$1');
    $routes->post('barang/update/(:num)', 'Admin\Barang::update/$1');
    $routes->post('barang/hapus/(:num)', 'Admin\Barang::hapus/$1');
    $routes->get('pesanan', 'Admin\Pesanan::index');
    $routes->post('pesanan/status/(:num)', 'Admin\Pesanan::status/$1');
});
