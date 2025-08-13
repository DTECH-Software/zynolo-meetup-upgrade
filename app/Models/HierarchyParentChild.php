<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HierarchyParentChild extends Model
{
    use HasFactory;


    public function parent()
    {
        return $this->belongsTo(Hierarchy::class,'parent_id');
    }

    public function child()
    {
        return $this->belongsTo(Hierarchy::class,'child_id');
    }

}
