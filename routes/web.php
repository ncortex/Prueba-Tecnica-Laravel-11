<?php

use App\Http\Controllers\CharacterController;
use Illuminate\Support\Facades\Route;

Route::get('/test', [CharacterController::class, 'updateCharactersInfo']);
