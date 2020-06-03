<?php

namespace BristolSU\Module\DataEntry\Http\Requests\ParticipantApi;

use BristolSU\Module\DataEntry\ColumnTypes\ColumnTypeStore;
use Illuminate\Foundation\Http\FormRequest;

class RowStoreRequest extends FormRequest
{

    public function rules()
    {
        $rules = [];
        $schemas = settings('column_types', []);
        foreach($schemas as $rowId => $schema) {
            if(array_key_exists('visible', $schema) && $schema['visible']) {
                $rules['fields.' . $rowId] = $this->parseRules($schema);
            }
        }
        return array_merge([
            'fields' => 'required|array'
        ], $rules);
    }

    protected function parseRules($schema)
    {
        //Add always needed validation
        $validation = array_merge(
            app(ColumnTypeStore::class)->find($schema['type'])::requiredValidation(),
            (array) $schema['validation']
        );
        return implode('|', $validation);
    }

    public function attributes()
    {
        $attributes = [];
        $schemas = settings('column_types', []);
        foreach($schemas as $rowId => $schema) {
            $attributes['fields.' . $rowId] = $schema['header'];
        }
        return $attributes;
    }
    
}