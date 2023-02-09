<?php

use App\Http\Controllers\PersonaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/angular/v1')->group(function () {
    Route::get('/personas', [PersonaController::class, 'showPersons'])->name('show.personas');
    Route::post('/personas', [PersonaController::class, 'create'])->name('create.persona');
});
