<?php

namespace BristolSU\Module\DataEntry\Http\Controllers\ParticipantApi;

use BristolSU\Module\DataEntry\Http\Controllers\Controller;
use BristolSU\Module\DataEntry\Http\Requests\ParticipantApi\RowStoreRequest;
use BristolSU\Module\DataEntry\Models\Cell;
use BristolSU\Module\DataEntry\Models\Row;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RowController extends Controller
{

    public function index(Request $request)
    {
        return Row::forResource()
            ->with('cells')
            ->when($request->has('search'), function(Builder $query) use ($request) {
                return $query->whereHas('cells', function(Builder $query) use ($request) {
                    return $query->where('value', 'LIKE', '%' . $request->input('search') . '%');
                });
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(
                $request->input('per_page', 30)
            );
    }

    public function update(Activity $activity, ModuleInstance $moduleInstance, Row $row, RowStoreRequest $request)
    {
        foreach($request->input('fields') as $columnId => $value) {
            Cell::updateOrCreate(
                ['row_id' => $row->id, 'column_id' => $columnId],
                ['value' => $value]);
        }
        return $row->refresh()->load('cells');
    }

    public function store(RowStoreRequest $request)
    {
        $row = Row::create();
        
        foreach($request->input('fields') as $columnId => $value) {
            Cell::create([
                'row_id' => $row->id,
                'column_id' => $columnId,
                'value' => $value
            ]);
        }
        
        return $row->load('cells');
    }

    public function destroy(Activity $activity, ModuleInstance $moduleInstance, Row $row)
    {
        $row->delete();
        return $row;
    }
    
}