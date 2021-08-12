<?php

use BristolSU\Module\DataEntry\Http\Controllers\AdminApi\ActivityInstanceController;
use BristolSU\Module\DataEntry\Http\Controllers\AdminApi\ActivityInstanceRowController;
use BristolSU\Module\DataEntry\Http\Controllers\AdminApi\CellValidationController;
use BristolSU\Module\DataEntry\Http\Controllers\AdminApi\RowController;
use Illuminate\Support\Facades\Route;

Route::apiResource('activity-instance', ActivityInstanceController::class);
Route::apiResource('activity-instance/{activity_instance}/row', ActivityInstanceRowController::class);
Route::apiResource('row', RowController::class);
Route::post('cell/{rowId}/validate', [CellValidationController::class, 'validateCell']);
