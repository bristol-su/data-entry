<?php

namespace BristolSU\Module\DataEntry\Http\Controllers\Participant;

use BristolSU\Module\DataEntry\Http\Controllers\Controller;

class PageController extends Controller
{

    public function index()
    {
        $this->authorize('view-page');
        
        return view('data-entry::participant');
    }
    
}