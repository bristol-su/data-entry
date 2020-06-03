<?php

namespace BristolSU\Module\DataEntry\Models;

class ActivityInstance extends \BristolSU\Support\ActivityInstance\ActivityInstance
{

    public function rows()
    {
        return $this->hasMany(Row::class, 'activity_instance_id', 'id');
    }
    
}