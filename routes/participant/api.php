<?php

use BristolSU\Module\DataEntry\Http\Controllers\ParticipantApi\CellValidationController;
use BristolSU\Module\DataEntry\Http\Controllers\ParticipantApi\CsvUploadController;
use BristolSU\Module\DataEntry\Http\Controllers\ParticipantApi\RowController;
use Illuminate\Support\Facades\Route;

Route::apiResource('row', RowController::class);
Route::post('csv', [CsvUploadController::class, 'upload']);
Route::post('cell/{rowId}/validate', [CellValidationController::class, 'validateCell']);
