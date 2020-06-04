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
            ['text' => 'Past Date', 'value' => 'before:today'],
            ['text' => 'Past Date (Including Today)', 'value' => 'before_or_equal:today'],
            ['text' => 'Future Date', 'value' => 'after:today'],
            ['text' => 'Future Date (Including Today)', 'value' => 'after_or_equal:today'],
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