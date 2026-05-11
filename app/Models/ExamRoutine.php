<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamRoutine extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'assignClass', 'assignDepartment', 'assignSession', 'attachment'
    ];

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

    public function entries()
    {
        return $this->hasMany(\App\Models\ExamRoutineItem::class, 'exam_routine_id')->orderBy('sort_order')->orderBy('exam_date');
    }
}
