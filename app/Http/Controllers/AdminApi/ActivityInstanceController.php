<?php

namespace BristolSU\Module\DataEntry\Http\Controllers\AdminApi;

use BristolSU\Module\DataEntry\Http\Controllers\Controller;
use BristolSU\Module\DataEntry\Models\ActivityInstance;
use BristolSU\Module\DataEntry\Models\Row;
use Illuminate\Http\Request;

class ActivityInstanceController extends Controller
{

    public function index(Request $request)
    {
        $this->authorize('admin.view-page');

        return ActivityInstance::query()->whereIn('id', Row::forModuleInstance()->pluck('activity_instance_id')->unique())
            ->withCount('rows')
            ->paginate($request->input('per_page', 20));
    }
    
}