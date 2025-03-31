<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Models\Ticket;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\UserController;
use App\Models\User;
// universal resource locator
// tickets
// users
// contracts



Route::middleware('auth:sanctum')->apiResource('tickets', TicketController::class);
Route::middleware('auth:sanctum')->apiResource('users', UserController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});