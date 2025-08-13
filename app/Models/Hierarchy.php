<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hierarchy extends Model
{
    use HasFactory;

    protected $fillable = [
        'hierarchy_level_id',
        'hierarchy_name',
        'abbreviation',
        'telephone',
        'fax',
        'email',
        'head_id',
        'title_of_head',
        'admin_id',
        'country_id',
        'logo_path',
        'header_path',
        'footer_path',
        'age_of_retirement',
        'weekend_day_1',
        'weekend_day_2',
        'monthly_short_leave_allowance',
        'monthly_short_leave_attempts',
    ];

    public function parents()
    {
        return $this->belongsToMany(Hierarchy::class,'hierarchy_parent_children','child_id','parent_id');
    }

    public function children()
    {
        return $this->belongsToMany(Hierarchy::class,'hierarchy_parent_children','parent_id','child_id');
    }

    public function employees(){
       return $this->hasMany(Employee::class,'company_id','id');
    }


}
