<?php

use App\Http\Controllers\Api\PortfolioController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::get('/get-portfolio', [PortfolioController::class, 'index']);
});
