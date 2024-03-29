<?php

namespace BristolSU\Module\DataEntry\Field;

use BristolSU\Module\DataEntry\ColumnTypes\ColumnTypeStore;
use FormSchema\Schema\Field;
use FormSchema\Transformers\Transformer;

class ColumnTypes extends Field
{

    protected $globalValidation = [
        ['text' => 'Required', 'value' => 'required'],
        ['text' => 'Optional', 'value' => 'nullable'],
        ['text' => 'Minimum Value', 'value' => 'min:0'],
        ['text' => 'Maximum Value', 'value' => 'max:100'],
        ['text' => 'Size is Between', 'value' => 'between:0,100']
    ];

    public function getAppendedAttributes(): array
    {
        [$fieldTypes, $fieldValidation, $fieldConfiguration] = static::getFieldInformation();
        return [
            'fieldTypes' => $fieldTypes,
            'globalValidation' => $this->globalValidation,
            'fieldValidation' => $fieldValidation,
            'fieldConfiguration' => $fieldConfiguration
        ];
    }

    public static function getFieldInformation()
    {
        $columnTypeStore = app(ColumnTypeStore::class);
        $fieldTypes = [];
        $fieldValidation = [];
        $fieldConfiguration = [];
        foreach($columnTypeStore->aliases() as $alias) {
            $field = $columnTypeStore->find($alias);
            $fieldTypes[] = ['value' => $alias, 'text' => $field::name()];
            $fieldValidation[$alias] = $field::validation();
            $config = $field::configuration();
            if($config) {
                $fieldConfiguration[$alias] = app(Transformer::class)->transformToArray($config);
            } else {
                $fieldConfiguration[$alias] = null;
            }
        }
        return [$fieldTypes, $fieldValidation, $fieldConfiguration];
    }

    public function getType(): string
    {
        return 'p-data-entry-column-types';
    }
}
