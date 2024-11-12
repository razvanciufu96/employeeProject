<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api-documentation', function () {
    $path = storage_path('employee_api_documentation.md');

    if (!File::exists($path)) {
        abort(404, 'Documentation not found.');
    }

    $markdown = File::get($path);

    $parsedown = new Parsedown();
    $htmlContent = $parsedown->text($markdown);

    return view('docs.show', compact('htmlContent'));
});
