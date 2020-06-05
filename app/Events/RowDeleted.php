<?php

namespace BristolSU\Module\DataEntry\Events;

use BristolSU\Module\DataEntry\Models\Row;
use BristolSU\Support\Action\Contracts\TriggerableEvent;
use Carbon\Carbon;

class RowDeleted implements TriggerableEvent
{

    /**
     * @var Row
     */
    private Row $row;

    public function __construct(Row $row)
    {
        $this->row = $row;
    }

    public function getFields(): array
    {
        $createdBy = $this->row->createdBy();
        $createdByData = $createdBy->data();
        $activityInstance = $this->row->activityInstance;
        return [
            'row_id' => $this->row->id,
            'activity_instance_id' => $activityInstance->id,
            'module_instance_id' => $this->row->module_instance_id,
            'activity_id' => $activityInstance->activity_id,
            'resource_type' => $activityInstance->resource_type,
            'resource_id' => $activityInstance->resource_id,
            'created_by' => $createdBy->id(),
            'created_by_first_name' => $createdByData->firstName(),
            'created_by_last_name' => $createdByData->lastName(),
            'created_by_preferred_name' => $createdByData->preferredName(),
            'created_by_email' => $createdByData->email(),
            'cells' => $this->row->cells->pluck('value', 'column_id'),
            'created_at' => Carbon::make($this->row->created_at)->format('Y-m-d H:i:s'),
            'deleted_at' => Carbon::make($this->row->deleted_at)->format('Y-m-d H:i:s')
        ];
    }

    public static function getFieldMetaData(): array
    {
        return [
            'row_id' => [
                'label' => 'Row ID',
                'helptext' => 'The ID of this row'
            ],
            'module_instance_id' => [
                'label' => 'Module Instance ID',
                'helptext' => 'The ID of the module instance'
            ],
            'activity_instance_id' => [
                'label' => 'Activity Instance ID',
                'helptext' => 'ID of the activity instance'
            ],
            'activity_id' => [
                'label' => 'Activity ID',
                'helptext' => 'The ID of this activity'
            ],
            'resource_type' => [
                'label' => 'Resource Type',
                'helptext' => 'The type of the resource (User, Group or Role)'
            ],
            'resource_id' => [
                'label' => 'Resource ID',
                'helptext' => 'The ID of the resource (User, Group or Role ID)'
            ],
            'created_by' => [
                'label' => 'Created By ID',
                'helptext' => 'ID of the user who created the row'
            ],
            'created_by_first_name' => [
                'label' => 'First Name',
                'helptext' => 'First name of the user who created the row'
            ],
            'created_by_last_name' => [
                'label' => 'Last Name',
                'helptext' => 'Last name of the user who created the row'
            ],
            'created_by_preferred_name' => [
                'label' => 'Preferred Name',
                'helptext' => 'Preferred name of the user who created the row'
            ],
            'created_by_email' => [
                'label' => 'Email',
                'helptext' => 'Email of the user who created the row'
            ],
            'cells' => [
                'label' => 'Cells',
                'helptext' => 'Cell data. The object key is the cell ID, and the value is the value'
            ],
            'created_at' => [
                'label' => 'Created At',
                'helptext' => 'Time created at'
            ],
            'deleted_at' => [
                'label' => 'Deleted At',
                'helptext' => 'When the row was deleted'
            ]
        ];
    }
}