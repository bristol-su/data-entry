<?php

namespace BristolSU\Module\DataEntry\Http\Controllers\ParticipantApi;

use BristolSU\Module\DataEntry\ColumnTypes\ColumnTypeStore;
use BristolSU\Module\DataEntry\Http\Controllers\Controller;
use BristolSU\Module\DataEntry\Models\Cell;
use BristolSU\Module\DataEntry\Models\Row;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use League\Csv\Reader;
use SplFileObject;

class CsvUploadController extends Controller
{

    public function upload(Request $request)
    {
        $request->validate(['file' => 'required|file']);
        $csv = Reader::createFromFileObject(new SplFileObject($request->file('file')->path()));

        $rowIds = [];
        $attributes = [];
        $schemas = settings('column_types', []);
        foreach ($schemas as $rowId => $schema) {
            if (array_key_exists('visible', $schema) && $schema['visible']) {
                $rowIds[$schema['header']] = $rowId;
                for($i=1;$i <= $csv->count() ; $i++) {
                    $attributes['data.' . $i . '.' . $rowId] = sprintf('Row %d, Column %s', $i, $schema['header']);
                }
            }
        }

        $csv->setHeaderOffset(0);
        $headers = $csv->getHeader();

        $orderedHeaders = [];
        foreach ($headers as $header) {
            if (!array_key_exists($header, $rowIds)) {
                return new Response(
                    ['errors' =>
                        [$header => sprintf('The header %s is not recognised. Please download a new template', $header)]
                    ], 422
                );
            }
            $orderedHeaders[] = $rowIds[$header];
        }
        
        $validator = Validator::make(['data' => iterator_to_array($csv->getRecords($orderedHeaders))], $this->rules(), [], $attributes)->validate();
        
        $rows = [];
        foreach($csv->getRecords($orderedHeaders) as $csvRow) {
            $row = Row::create();
            foreach($csvRow as $columnId => $value) {
                Cell::create([
                    'row_id' => $row->id,
                    'column_id' => $columnId,
                    'value' => $value
                ]);
            }
            $rows[] = $row;
        }   
        
        return $rows;
    }


    public function rules()
    {
        $rules = [];
        $schemas = settings('column_types', []);
        foreach ($schemas as $rowId => $schema) {
            if (array_key_exists('visible', $schema) && $schema['visible']) {
                $rules['data.*.' . $rowId] = $this->parseRules($schema);
            }
        }
        return array_merge([
            'data' => 'required|array|min:1',
            'data.*' => 'array',
        ], $rules);
    }

    protected function parseRules($schema)
    {
        //Add always needed validation
        $validation = array_merge(
            app(ColumnTypeStore::class)->find($schema['type'])::requiredValidation(),
            (array)$schema['validation']
        );
        return implode('|', $validation);
    }
}
