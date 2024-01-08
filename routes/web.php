<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login', [
        'js' => [
            'js/jquery.min.js',
            'dist/js/adminlte.min.js',
            'js/helper.js',
            'js/login.js',
        ],
        'css' => [
            'plugins/fontawesome-free/css/all.min.css',
            'plugins/icheck-bootstrap/icheck-bootstrap.min.css',
            'dist/css/adminlte.min.css'
        ]
    ]);
});

Route::get('/contacts', function () {
    return view('contacts', [
        'js' => [
            'js/jquery.min.js',
            'plugins/bootstrap/js/bootstrap.bundle.min.js',
            'dist/js/adminlte.min.js',
            'js/helper.js',
            'js/contacts.js'
        ],
        'css' => [
            'plugins/fontawesome-free/css/all.min.css',
            'dist/css/adminlte.min.css'
        ]
    ]);
});

Route::get('/register', function () {
    return view('register', [
        'js' => [
            'js/jquery.min.js',
            'plugins/bootstrap/js/bootstrap.bundle.min.js',
            'dist/js/adminlte.min.js',
            'js/helper.js',
            'js/register.js',
        ],
        'css'=>[
            'plugins/fontawesome-free/css/all.min.css',
            'plugins/icheck-bootstrap/icheck-bootstrap.min.css',
            'dist/css/adminlte.min.css'
        ]
    ]);
});


Route::get('/thank-you-page', function () {
    return view('thank-you', [
        'js' => [
            'js/jquery.min.js',
            'plugins/bootstrap/js/bootstrap.bundle.min.js',
            'dist/js/adminlte.min.js',
            'js/helper.js',
        ],
        'css'=>[
            'plugins/fontawesome-free/css/all.min.css',
            'plugins/icheck-bootstrap/icheck-bootstrap.min.css',
            'dist/css/adminlte.min.css'
        ]
    ]);
});

