<?php

namespace BristolSU\Module\DataEntry\Models;

use BristolSU\ControlDB\Contracts\Repositories\User;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\Authentication\HasResource;
use BristolSU\Support\Revision\HasRevisions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;

class Row extends Model
{
    use HasResource, SoftDeletes, HasRevisions;
    
    protected $table = 'data_entry_row';
    
    protected $fillable = [
        'activity_instance_id'
    ];
    
    protected static function boot()
    {
        parent::boot();
        static::creating(function($row) {
            if($row->created_by === null) {
                $row->created_by = app(Authentication::class)->getUser()->id();
            }
            return $row;
        });
    }

    /**
     * @return \BristolSU\ControlDB\Contracts\Models\User
     * 
     * @throws ModelNotFoundException
     */
    public function createdBy()
    {
        return app(User::class)->getById($this->created_by);
    }

    public function scopeCurrentCell(Builder $query, $columnId)
    {
        return $this->cells()->where('column_id', $columnId);
    }

    public function cells()
    {
        return $this->hasMany(Cell::class);
    }

    public function activityInstance()
    {
        return $this->belongsTo(ActivityInstance::class);
    }
}