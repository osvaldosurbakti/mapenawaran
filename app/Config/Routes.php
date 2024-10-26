<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes = Services::routes();

// Load system's routing file first, so that the app and ENVIRONMENT can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login'); // Mengatur controller default menjadi Login
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Route ke halaman utama (login page)
$routes->get('/', 'Login::index');

// Route untuk login page
$routes->get('login', 'Login::index');
$routes->post('login/authenticate', 'Login::authenticate'); // Untuk menangani POST login

// Route untuk logout
$routes->get('logout', 'Login::logout');

// Route untuk registrasi
$routes->get('register', 'Login::register');
$routes->post('register/create', 'Login::create'); // Untuk menangani POST registrasi

// Dashboard routes
$routes->get('dashboard/(:any)/(:num)', 'Dashboard::index/$1/$2');

// Route untuk buat penawaran
$routes->get('penawaran/create', 'Penawaran::create');
$routes->post('penawaran/store', 'Penawaran::store');

// Route untuk detail penawaran
$routes->get('penawaran/detail/(:num)', 'Penawaran::detail/$1', ['filter' => 'auth']);

// Route untuk cancel penawaran
$routes->get('penawaran/cancel/(:num)', 'Penawaran::cancel/$1', ['filter' => 'auth']);

// Route untuk history
$routes->get('history', 'History::index', ['filter' => 'auth']);
$routes->get('dashboard/history', 'Dashboard::history', ['filter' => 'auth']);

// Route untuk mengambil keputusan terhadap penawaran
$routes->post('penawaran/decision/(:num)', 'Penawaran::decision/$1', ['filter' => 'auth']);


/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. You can
 * use the `$routes->load` method to include additional route files.
 */

if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
