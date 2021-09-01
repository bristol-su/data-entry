<?php

namespace BristolSU\Module\DataEntry\Field;

use FormSchema\Schema\Field;

class ArrayField extends Field
{

    protected $showRemoveButton = true;

    public function getAppendedAttributes(): array
    {
        return [
            'showRemoveButton' => $this->showRemoveButton
        ];
    }

    public function showRemoveButton(bool $showRemoveButton): ArrayField
    {
        $this->showRemoveButton = $showRemoveButton;
        return $this;
    }

    public function getType(): string
    {
        return 'p-data-entry-array';
    }
}
