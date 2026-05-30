<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
// $routes->get('/', 'Home::index');
// Routes Publik
$routes->get('/', 'Voter::registrasi'); // Halaman depan langsung form Fase 1
$routes->get('vote/(:segment)', 'Voter::bilik_suara/$1'); // URL pendek: domain.com/vote/token
$routes->get('nominasi/(:segment)', 'Voter::nominasi/$1');

$routes->get('nominasi', 'Voter::nominasi');
$routes->get('vote', 'Voter::bilik_suara');

// Routes Otentikasi Admin
$routes->get('login', 'Auth::index');
$routes->post('login/process', 'Auth::process');
$routes->get('logout', 'Auth::logout');

// Routes Admin (Dilindungi Filter Auth)
$routes->group('panel', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Admin::index');
    $routes->get('dpt', 'Admin::verifikasi_dpt');
    $routes->get('nominasi', 'Admin::nominasi');
    $routes->get('live', 'Admin::live_diagram');
    $routes->get('kandidat', 'Admin::kandidat');
    $routes->post('kandidat/upload', 'Admin::upload_kandidat_foto');
    $routes->post('broadcast-wa', 'Webhook::kirim_undangan_wa');
    $routes->get('pengaturan', 'Admin::pengaturan');
    $routes->post('pengaturan/upload', 'Admin::upload_logo');
    $routes->post('pengaturan/sync', 'Admin::sync_config');
});
