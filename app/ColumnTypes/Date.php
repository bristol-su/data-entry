<?php

namespace BristolSU\Module\DataEntry\ColumnTypes;

use FormSchema\Schema\Form;

class Date extends ColumnType
{

    public static function name(): string
    {
        return 'Date';
    }

    public static function validation(): array
    {
        return [
            ['text' => 'A Test', 'value' => 'test'],
        ];
    }
    
    public static function configuration(): ?Form
    {
        return null;
    }

    public static function requiredValidation(): array
    {
        return ['date_format:Y-m-d'];
    }
}