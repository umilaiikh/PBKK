<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Auth::login');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');

$routes->get('dashboard', 'Auth::dashboard');

$routes->group('admin', [
    'filter' => 'role:admin',
    'namespace' => 'App\Controllers\Admin'
], function ($routes) {
    $routes->get('/', 'Dashboard::index');

    $routes->get('mahasiswa', 'Mahasiswa::index');
    $routes->get('mahasiswa/create', 'Mahasiswa::create');
    $routes->post('mahasiswa/store', 'Mahasiswa::store');
    $routes->get('mahasiswa/edit/(:num)', 'Mahasiswa::edit/$1');
    $routes->post('mahasiswa/update/(:num)', 'Mahasiswa::update/$1');
    $routes->post('mahasiswa/delete/(:num)', 'Mahasiswa::delete/$1');

    $routes->get('dosen', 'Dosen::index');
    $routes->post('dosen/store', 'Dosen::store');
    $routes->post('dosen/delete/(:num)', 'Dosen::delete/$1');

    $routes->get('matakuliah', 'Matakuliah::index');
    $routes->get('matakuliah/create', 'Matakuliah::create');
    $routes->post('matakuliah/store', 'Matakuliah::store');
    $routes->get('matakuliah/edit/(:num)', 'Matakuliah::edit/$1');
    $routes->post('matakuliah/update/(:num)', 'Matakuliah::update/$1');
    $routes->post('matakuliah/delete/(:num)', 'Matakuliah::delete/$1');
});

$routes->group('dosen', [
    'filter' => 'role:dosen',
    'namespace' => 'App\Controllers\Dosen'
], function ($routes) {
    $routes->get('/', 'Dashboard::index');

    $routes->get('kelas', 'Kelas::index');
    $routes->get('kelas/peserta/(:num)', 'Kelas::peserta/$1');
    $routes->get('kelas/nilai/(:num)', 'Kelas::nilai/$1');
    $routes->post('kelas/nilai/simpan/(:num)', 'Kelas::simpanNilai/$1');
    $routes->get('bimbingan', 'Kelas::bimbingan');
});

$routes->group('mahasiswa', [
    'filter' => 'role:mahasiswa',
    'namespace' => 'App\Controllers\Mahasiswa'
], function ($routes) {
    $routes->get('/', 'Dashboard::index');

    $routes->get('krs', 'Akademik::krs');
    $routes->post('krs/tambah', 'Akademik::tambahKrs');
    $routes->post('krs/hapus/(:num)', 'Akademik::hapusKrs/$1');
    $routes->get('khs', 'Akademik::khs');
    $routes->get('transkrip', 'Akademik::transkrip');
});