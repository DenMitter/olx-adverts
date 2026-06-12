<?php

use App\Http\Controllers\Api\V1\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::post('/subscriptions', [SubscriptionController::class, 'store']);
Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'confirm'])
    ->name('subscriptions.confirm')
    ->middleware('signed');