<?php

namespace BristolSU\Module\DataEntry;

use BristolSU\Support\Module\ModuleServiceProvider as ServiceProvider;
use FormSchema\Schema\Form;

class ModuleServiceProvider extends ServiceProvider
{

    protected $permissions = [
        'view-page' => [
            'name' => 'View Participant Page',
            'description' => 'View the main page of the module.',
            'admin' => false
        ],
        'admin.view-page' => [
            'name' => 'View Admin Page',
            'description' => 'View the administrator page of the module.',
            'admin' => true
        ]
    ];

    protected $events = [

    ];
    
    protected $commands = [
        
    ];
    
    public function alias(): string
    {
        return 'data-entry';
    }

    public function namespace()
    {
        return '\BristolSU\Module\DataEntry\Http\Controllers';
    }
    
    public function baseDirectory()
    {
        return __DIR__ . '/..';
    }

    public function boot()
    {
        parent::boot();
    }

    public function register()
    {
        parent::register();
    }

    /**
     * @inheritDoc
     */
    public function settings(): Form
    {
        return \FormSchema\Generator\Form::make()->getSchema();
    }
}
