<?php

namespace BristolSU\Module\DataEntry;

use BristolSU\Module\DataEntry\ColumnTypes\ColumnTypeStore;
use BristolSU\Module\DataEntry\ColumnTypes\Date;
use BristolSU\Module\DataEntry\ColumnTypes\Decimal;
use BristolSU\Module\DataEntry\ColumnTypes\LongText;
use BristolSU\Module\DataEntry\ColumnTypes\Number;
use BristolSU\Module\DataEntry\ColumnTypes\Select;
use BristolSU\Module\DataEntry\ColumnTypes\Text;
use BristolSU\Module\DataEntry\Events\RowAdded;
use BristolSU\Module\DataEntry\Events\RowDeleted;
use BristolSU\Module\DataEntry\Events\RowUpdated;
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
        'use-csv' => [
            'name' => 'Use CSV Uploads',
            'description' => 'Allow a user to upload a CSV',
            'admin' => false
        ],
        'download-csv' => [
            'name' => 'Download a CSV',
            'description' => 'Allow a user to download a CSV of their answers',
            'admin' => false
        ],
        'row.update' => [
            'name' => 'Update a Row',
            'description' => 'Allow a user to update a row',
            'admin' => false
        ],
        'row.store' => [
            'name' => 'Create a Row',
            'description' => 'Allow a user to create a new row',
            'admin' => false
        ],
        'row.delete' => [
            'name' => 'Delete a Row',
            'description' => 'Allow a user to delete a row',
            'admin' => false
        ],
        'admin.view-page' => [
            'name' => 'View Admin Page',
            'description' => 'View the administrator page of the module.',
            'admin' => true
        ],
        'admin.download-csv' => [
            'name' => 'Download CSV',
            'description' => 'Allow downloading a CSV of inputted data',
            'admin' => true
        ],
        'admin.row.update' => [
            'name' => 'Update a Row',
            'description' => 'Allow a user to update a row',
            'admin' => true
        ],
        'admin.row.store' => [
            'name' => 'Create a Row',
            'description' => 'Allow a user to create a new row',
            'admin' => true
        ],
        'admin.row.delete' => [
            'name' => 'Delete a Row',
            'description' => 'Allow a user to delete a row',
            'admin' => true
        ],
    ];

    protected $events = [
        RowAdded::class => [
            'name' => 'Row Added',
            'description' => 'When a new row is added'
        ],
        RowUpdated::class => [
            'name' => 'Row Updated',
            'description' => 'When a new row is updated'
        ],
        RowDeleted::class => [
            'name' => 'Row Deleted',
            'description' => 'When a new row is deleted'
        ]
    ];

    protected $commands = [

    ];

    public function alias(): string
    {
        return 'data-entry';
    }

    public function namespace()
    {
        return null;
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
                Field::textInput('title')->setLabel('Title')->setRequired(true)
                    ->setValue('Page Title')->setHint('The title of the page')
                    ->setTooltip('This title will be shown at the top of the page, to help users understand what the module is for')
            )->withField(
                Field::textInput('subtitle')->setLabel('Subtitle')->setRequired(true)
                    ->setValue('Page Subtitle')->setHint('The subtitle for the page')
                    ->setTooltip('This subtitle will be shown under the title, and should include more information for users')
            )
        )->withGroup(
            Group::make('Data')->withField(
                Field::make(ColumnTypes::class, 'column_types')
                    ->setLabel('Table Definition')
            )
        )->getSchema();

    }
}
