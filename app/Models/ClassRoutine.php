<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoutine extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'assignClass',
        'assignSection',
        'assignDepartment',
        'assignSession',
        'attachment'
    ];

    // Define relations to avoid repeated find() calls in views
    public function class()
    {
        return $this->belongsTo(\App\Models\classManage::class, 'assignClass');
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\Department::class, 'assignDepartment');
    }

    public function session()
    {
        return $this->belongsTo(\App\Models\sessionManage::class, 'assignSession');
    }

    // V2-style entries relation
    public function entries()
    {
        return $this->hasMany(\App\Models\ClassRoutineItem::class, 'class_routine_id')->orderBy('sort_order')->orderBy('id');
    }
}
