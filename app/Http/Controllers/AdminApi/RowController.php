<?php

namespace BristolSU\Module\DataEntry\Http\Controllers\AdminApi;

use BristolSU\Module\DataEntry\Events\RowAdded;
use BristolSU\Module\DataEntry\Events\RowDeleted;
use BristolSU\Module\DataEntry\Events\RowUpdated;
use BristolSU\Module\DataEntry\Http\Controllers\Controller;
use BristolSU\Module\DataEntry\Http\Requests\ParticipantApi\RowStoreRequest;
use BristolSU\Module\DataEntry\Models\Cell;
use BristolSU\Module\DataEntry\Models\Row;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class RowController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('admin.view-page');

        return Row::forModuleInstance()
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

    public function destroy(Activity $activity, ModuleInstance $moduleInstance, Row $row)
    {
        $this->authorize('admin.row.delete');

        $row->delete();
        Event::dispatch(new RowDeleted($row));
        
        return $row;
    }

    public function store(RowStoreRequest $request)
    {
        $this->authorize('admin.row.store');

        $row = Row::create(['activity_instance_id' => $request->input('activity_instance_id')]);

        foreach($request->input('fields') as $columnId => $value) {
            if($value !== null) {
                Cell::create([
                    'row_id' => $row->id,
                    'column_id' => $columnId,
                    'value' => $value,
                ]);
            }
        }

        Event::dispatch(new RowAdded($row));

        return $row->load('cells');
    }

    public function update(Activity $activity, ModuleInstance $moduleInstance, Row $row, RowStoreRequest $request)
    {
        $this->authorize('admin.row.update');
        
        foreach($request->input('fields') as $columnId => $value) {
            Cell::updateOrCreate(
                ['row_id' => $row->id, 'column_id' => $columnId],
                ['value' => $value]);
        }
        $row->refresh();
        
        Event::dispatch(new RowUpdated($row));

        return $row->load('cells');
    }

}