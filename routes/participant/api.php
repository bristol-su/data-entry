<?php

use Illuminate\Support\Facades\Route;

Route::namespace('ParticipantApi')->group(function() {
    Route::apiResource('row', 'RowController');
    Route::post('csv', 'CsvUploadController@upload');
    Route::post('cell/{rowId}/validate', 'CellValidationController@validateCell');
});
