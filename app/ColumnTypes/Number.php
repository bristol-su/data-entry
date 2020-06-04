<?php

namespace BristolSU\Module\DataEntry\ColumnTypes;

use FormSchema\Schema\Form;

class Number extends ColumnType
{

    public static function name(): string
    {
        return 'Number (Integer)';
    }

    public static function validation(): array
    {
        return [
            ['text' => 'Starts With', 'value' => 'starts_with:option1,option2'],
            ['text' => 'Ends With', 'value' => 'starts_with:option1,option2'],
            ['text' => 'Number of Digits', 'value' => 'digits:3'],
            ['text' => 'Number of Digits Between', 'value' => 'digits_between:2,4']
        ];
    }
    
    public static function configuration(): ?Form
    {
        return null;
    }

    public static function requiredValidation(): array
    {
        return ['numeric'];
    }
}