<?php

namespace BristolSU\Module\DataEntry\Http\Controllers\Admin;

use BristolSU\Module\DataEntry\Http\Controllers\Controller;

class PageController extends Controller
{

    public function index()
    {
        $this->authorize('admin.view-page');

        return view('data-entry::admin');
    }

}