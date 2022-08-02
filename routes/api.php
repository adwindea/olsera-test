<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PajakController;

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

Route::resource('item', ItemController::class);
Route::resource('pajak', PajakController::class);

Route::prefix('item')->group(function () {
    Route::post('/addPajakToItem', [ItemController::class, 'addPajakToItem'])->name('item.addPajakToItem');
});
