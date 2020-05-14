<?php

namespace BristolSU\Module\DataEntry\Http\Controllers;

class AdminPageController extends Controller
{
    
    public function index()
    {
        $this->authorize('admin.view-page');
        
        return view('data-entry::admin');
    }
    
}