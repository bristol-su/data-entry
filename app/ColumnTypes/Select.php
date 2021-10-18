<?php

namespace BristolSU\Module\DataEntry\ColumnTypes;

use BristolSU\Module\DataEntry\Field\ArrayField;
use FormSchema\Generator\Field;
use FormSchema\Schema\Form;

class Select extends ColumnType
{

    public static function name(): string
    {
        return 'Selection';
    }

    public static function validation(): array
    {
        return [];
    }

    public static function configuration(): Form
    {
        return \FormSchema\Generator\Form::make()->withField(
            Field::array('select_options')->setLabel('Select Options')
                ->showRemoveButton(true)
        )->withField(
            Field::checkBox('add_own')->setLabel('May the user add their own options?')
        )->getSchema();
    }

    public static function requiredValidation(): array
    {
        return [];
    }
}
