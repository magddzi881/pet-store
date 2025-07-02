<?php

use App\Http\Controllers\PetController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [PetController::class, 'index']);
Route::get('/pet/create', [PetController::class, 'create']);
Route::post('/pet', [PetController::class, 'save']);
Route::get('/pet/{id}/edit', [PetController::class, 'edit']);
Route::put('/pet/{id}', [PetController::class, 'update']);
Route::delete('/pet/{id}', [PetController::class, 'delete']);
