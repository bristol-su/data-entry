<?php

namespace BristolSU\Module\DataEntry\Models;

class ActivityInstance extends \BristolSU\Support\ActivityInstance\ActivityInstance
{

    public function rows()
    {
        return $this->hasMany(Row::class, 'activity_instance_id', 'id');
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
