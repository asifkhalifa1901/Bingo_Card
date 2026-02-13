<?php

use App\Http\Controllers\BingoCardController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::post('/cards/generate', [BingoCardController::class, 'generate'])->name('cards.generate');
Route::get('/cards/templates', [BingoCardController::class, 'templates'])->name('cards.templates');
Route::get('/cards/{card:uuid}', [BingoCardController::class, 'show'])->name('cards.show');
Route::get('/cards/{card:uuid}/edit', [BingoCardController::class, 'edit'])->name('cards.edit');
Route::put('/cards/{card:uuid}', [BingoCardController::class, 'update'])->name('cards.update');
Route::get('/cards/{card:uuid}/download', [BingoCardController::class, 'download'])->name('cards.download');
