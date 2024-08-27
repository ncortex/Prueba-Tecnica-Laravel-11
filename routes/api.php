<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'postLogin'])->name('login');
Route::post('/register', [AuthController::class, 'postRegister'])->name('register');

Route::get('/characters', [CharacterController::class, 'getCharacters']);
Route::get('/characters/{id}', [CharacterController::class, 'getCharacter'])->where(['id' => '[0-9]+']);

Route::get('/favorites', [UserController::class, 'getFavorites']);
Route::post('/favorites', [UserController::class, 'postFavorite']);
Route::delete('/favorites', [UserController::class, 'deleteFavorite']);

