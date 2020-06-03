<?php

namespace BristolSU\Module\DataEntry\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller
{
    use AuthorizesRequests {
        authorize as baseAuthorize;
    }
    
    use DispatchesJobs, ValidatesRequests;

    public function authorize($ability, $arguments = [])
    {
        return $this->baseAuthorize(
            'data-entry.' . $ability,
            $arguments
        );
    }
}