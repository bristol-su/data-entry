<?php

namespace BristolSU\Module\DataEntry;

use BristolSU\Module\DataEntry\ColumnTypes\ColumnTypeStore;
use BristolSU\Module\DataEntry\ColumnTypes\Date;
use BristolSU\Module\DataEntry\ColumnTypes\Decimal;
use BristolSU\Module\DataEntry\ColumnTypes\LongText;
use BristolSU\Module\DataEntry\ColumnTypes\Number;
use BristolSU\Module\DataEntry\ColumnTypes\Select;
use BristolSU\Module\DataEntry\ColumnTypes\Text;
use BristolSU\Module\DataEntry\Field\ColumnTypes;
use BristolSU\Support\Module\ModuleServiceProvider as ServiceProvider;
use FormSchema\Generator\Field;
use FormSchema\Generator\Group;
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
        $this->registerGlobalScript('modules/data-entry/js/components.js');
        ColumnTypeStore::add('number', Number::class);
        ColumnTypeStore::add('decimal', Decimal::class);
        ColumnTypeStore::add('select', Select::class);
        ColumnTypeStore::add('date', Date::class);
        ColumnTypeStore::add('text', Text::class);
        ColumnTypeStore::add('longtext', LongText::class);
    }

    public function register()
    {
        parent::register();
        $this->app->singleton(ColumnTypeStore::class);
    }

    /**
     * @inheritDoc
     */
    public function settings(): Form
    {
        return \FormSchema\Generator\Form::make()->withGroup(
            Group::make('Page Layout')->withField(
                Field::input('title')->inputType('text')->label('Title')->featured(false)->required(true)
                    ->default('Page Title')->hint('The title of the page')
                    ->help('This title will be shown at the top of the page, to help users understand what the module is for')
            )->withField(
                Field::input('subtitle')->inputType('text')->label('Subtitle')->featured(false)->required(true)
                    ->default('Page Subtitle')->hint('The subtitle for the page')
                    ->help('This subtitle will be shown under the title, and should include more information for users')
            )
        )->withGroup(
            Group::make('Data')->withField(
                Field::make(ColumnTypes::class, 'column_types')
                    ->label('Table Definition')
            )
        )->getSchema();
            
    }
}
