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
            ['text' => 'Starts With', 'value' => 'starts_with:option1,option2'],
            ['text' => 'Ends With', 'value' => 'starts_with:option1,option2'],
            ['text' => 'Timezone', 'value' => 'timezone'],
            ['text' => 'URL', 'value' => 'url'],
            ['text' => 'Active URL', 'value' => 'active_url'],
            ['text' => 'Email Address', 'value' => 'email'],
            ['text' => 'IP Address', 'value' => 'ip']
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