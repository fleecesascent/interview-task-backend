<?php

use App\Modules\Invoices\Api\InvoicesFacadeInterface;
use App\Modules\Invoices\Infrastructure\Http\Api\InvoicesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Ramsey\Uuid\Uuid;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/invoices', [InvoicesController::class, 'index']);
Route::patch('/invoices/{invoiceId}/approve', [InvoicesController::class, 'approveInvoice']);
Route::patch('/invoices/{invoiceId}/reject', [InvoicesController::class, 'rejectInvoice']);
