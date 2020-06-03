<?php

use Illuminate\Support\Facades\Route;

Route::namespace('AdminApi')->group(function() {
    Route::apiResource('activity-instance', 'ActivityInstanceController');
    Route::apiResource('activity-instance/{activity_instance}/row', 'ActivityInstanceRowController');
    Route::apiResource('row', 'RowController');
    Route::post('cell/{rowId}/validate', 'CellValidationController@validateCell');
});
