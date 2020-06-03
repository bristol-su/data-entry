<?php

namespace BristolSU\Module\DataEntry\Models;

use BristolSU\Support\Revision\HasRevisions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cell extends Model
{
    use HasRevisions, SoftDeletes;

    protected $table = 'data_entry_cell';
    
    protected $fillable = [
        'column_id',
        'value',
        'row_id',
    ];

    public function row()
    {
        return $this->belongsTo(Row::class);
    }
}