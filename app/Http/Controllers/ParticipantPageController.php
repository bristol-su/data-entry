<?php

namespace BristolSU\Module\DataEntry\Http\Controllers;

class ParticipantPageController extends Controller
{

    public function index()
    {
        $this->authorize('view-page');
        
        return view('data-entry::participant');
    }
    
}