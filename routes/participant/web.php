<?php

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

use BristolSU\Module\DataEntry\Http\Controllers\Participant\CsvDownloadController;
use BristolSU\Module\DataEntry\Http\Controllers\Participant\CsvTemplateDownloadController;
use BristolSU\Module\DataEntry\Http\Controllers\Participant\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index']);
Route::get('/csv/template', [CsvTemplateDownloadController::class, 'download']);
Route::get('/csv', [CsvDownloadController::class, 'download']);
