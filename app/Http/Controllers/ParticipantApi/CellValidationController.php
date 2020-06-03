<?php

namespace BristolSU\Module\DataEntry\Http\Controllers\ParticipantApi;

use BristolSU\Module\DataEntry\ColumnTypes\ColumnTypeStore;
use BristolSU\Module\DataEntry\Http\Controllers\Controller;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CellValidationController extends Controller
{

    protected function validatorFor($schema)
    {
        return array_merge(
            app(ColumnTypeStore::class)->find($schema['type'])::requiredValidation(),
            (array) $schema['validation']
        );
    }

    public function validateCell(Activity $activity, ModuleInstance $moduleInstance, $rowId, Request $request)
    {
        $this->authorize('view-page');
        $schemas = settings('column_types', []);
        if(array_key_exists($rowId, $schemas)) {
            $schema = $schemas[$rowId];
            $attribute = $schema['header'];
            $validator = Validator::make([$attribute => $request->input('value')], [$attribute => $this->validatorFor($schema)], [], []);

            if($validator->fails()) {
                return new Response($validator->errors()->get($attribute), 200);
            }
        }
        return new Response('', 204);
    }
    
}