<?php

use App\Http\Resources\ClientResource;
use App\Http\Resources\LoanResource;
use App\Http\Resources\PaymentResource;
use App\Models\Client;
use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());
    Route::get('/clients', fn () => ClientResource::collection(Client::paginate()));
    Route::get('/loans', fn () => LoanResource::collection(Loan::with('client')->paginate()));
    Route::get('/payments', fn () => PaymentResource::collection(Payment::with('loan')->paginate()));
});
