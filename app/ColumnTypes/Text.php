<?php

namespace BristolSU\Module\DataEntry\ColumnTypes;

use FormSchema\Schema\Form;

class Text extends ColumnType
{

    public static function name(): string
    {
        return 'Text';
    }

    public static function validation(): array
    {
        return [
        ];
    }
    
    public static function configuration(): ?Form
    {
        return null;
    }

    public static function requiredValidation(): array
    {
        return ['string', 'max:65535'];
    }
}