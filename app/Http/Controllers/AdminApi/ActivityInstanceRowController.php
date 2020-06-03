<?php

namespace BristolSU\Module\DataEntry\Http\Controllers\AdminApi;

use BristolSU\Module\DataEntry\Http\Controllers\Controller;
use BristolSU\Module\DataEntry\Http\Requests\ParticipantApi\RowStoreRequest;
use BristolSU\Module\DataEntry\Models\ActivityInstance;
use BristolSU\Module\DataEntry\Models\Cell;
use BristolSU\Module\DataEntry\Models\Row;
use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\ModuleInstance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ActivityInstanceRowController extends Controller
{

    public function index(Activity $activity, ModuleInstance $moduleInstance, ActivityInstance $activityInstance, Request $request)
    {
        $this->authorize('admin.view-page');

        return $activityInstance->rows()
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
    
}