<?php

namespace BristolSU\Module\DataEntry\Field;

use FormSchema\Schema\Field;

class ArrayField extends Field
{

    protected $type = 'dataEntryArray';

    protected $showRemoveButton = true;
    
    public function getAppendedAttributes(): array
    {
        return [
            'showRemoveButton' => $this->showRemoveButton
        ];
    }
}