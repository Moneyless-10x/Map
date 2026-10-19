use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/triage-locations', function () {
    return response()->json(
        DB::table('triage_locations')->get()
    );
});<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TriageLocationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// GET all locations for Leaflet Map
Route::get('/triage-locations', [TriageLocationController::class, 'getLocations']);

// POST new location from Map / forms (CSRF bypass active)
Route::post('/triage-locations', [TriageLocationController::class, 'store']);

// DELETE a location
Route::delete('/triage-locations/{id}', [TriageLocationController::class, 'destroy']);