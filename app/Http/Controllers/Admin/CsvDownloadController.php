<?php

namespace BristolSU\Module\DataEntry\Http\Controllers\Admin;

use BristolSU\Module\DataEntry\Http\Controllers\Controller;
use BristolSU\Module\DataEntry\Models\ActivityInstance;
use BristolSU\Module\DataEntry\Models\Row;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use League\Csv\Writer;

class CsvDownloadController extends Controller
{

    public function forActivityInstance(Activity $activity, ModuleInstance $moduleInstance, ActivityInstance $activityInstance)
    {
        $header = [];
        $colIds = collect();
        $schemas = settings('column_types', []);
        foreach($schemas as $rowId => $schema) {
            if(array_key_exists('visible', $schema) && $schema['visible']) {
                $header[] = $schema['header'];
                $colIds->push($rowId);
            }
        }
        
        $rows = Row::forResource($activityInstance->id)
            ->with('cells')
            ->get()
            ->map(function(Row $row) use ($colIds) {
                $cells = $row->cells->sortBy(function($value, $key) use ($colIds) {
                    return array_search($value->column_id, $colIds->toArray());
                }, SORT_REGULAR);
                return $colIds->map(function($colId) use (&$cells) {
                    if(count($cells) > 0 && $cells[0]->column_id === $colId) {
                        return $cells->shift()->value;
                    }
                    return null;
                });
            });
        $csv = Writer::createFromString();
        $csv->insertOne($header);
        $csv->insertAll($rows->toArray());
        
        return new Response($csv->getContent(), 200, [
            'Content-Encoding' => 'none',
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data.csv"',
            'Content-Description' => 'File Transfer',
            'X-Vapor-Base64-Encode' => 'True',
        ]);
    }

    public function forAll()
    {
        $header = [];
        $colIds = collect();
        $schemas = settings('column_types', []);
        foreach($schemas as $rowId => $schema) {
            if(array_key_exists('visible', $schema) && $schema['visible']) {
                $header[] = $schema['header'];
                $colIds->push($rowId);
            }
        }

        $rows = Row::forModuleInstance()
            ->with('cells')
            ->get()
            ->map(function(Row $row) use ($colIds) {
                $cells = $row->cells->sortBy(function($value, $key) use ($colIds) {
                    return array_search($value->column_id, $colIds->toArray());
                }, SORT_REGULAR);
                
                $participant = $row->activityInstance->participant->data();
                $resourceType = $row->activityInstance->resource_type;
                $initialData = collect([
                    $row->activityInstance->id, 
                    ($resourceType === 'user' ? $participant->firstName() . ' ' . $participant->lastName() :
                        ($resourceType === 'group' ? $participant->name() : $participant->roleName())
                    )
                ]);
                return $initialData->concat($colIds->map(function($colId) use (&$cells) {
                    if(count($cells) > 0 && $cells[0]->column_id === $colId) {
                        return $cells->shift()->value;
                    }
                    return null;
                }));
            });
        $csv = Writer::createFromString();
        $csv->insertOne(['Activity Instance ID', 'Participant', ...$header]);
        $csv->insertAll($rows->toArray());

        return new Response($csv->getContent(), 200, [
            'Content-Encoding' => 'none',
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="data.csv"',
            'Content-Description' => 'File Transfer',
            'X-Vapor-Base64-Encode' => 'True',
        ]);
    }
    
}