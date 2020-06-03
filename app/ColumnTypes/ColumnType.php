<?php

namespace BristolSU\Module\DataEntry\ColumnTypes;

use FormSchema\Schema\Form;

abstract class ColumnType
{

    abstract public static function name(): string;
    
    abstract public static function validation(): array;

    abstract public static function configuration(): ?Form;

    abstract public static function requiredValidation(): array;
}