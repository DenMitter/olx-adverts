<?php

use App\Http\Controllers\Api\V1\AdvertisementController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/subscriptions', [SubscriptionController::class, 'index']);
Route::get('/advertisements', [AdvertisementController::class, 'index']);
