<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamRoutineItem extends Model
{
    use HasFactory;

    protected $table = 'exam_routine_items';

    protected $fillable = [
        'exam_routine_id', 'exam_date', 'start_time', 'end_time', 'subject_name', 'venue', 'sort_order'
    ];

    public function routine()
    {
        return $this->belongsTo(ExamRoutine::class, 'exam_routine_id');
    }
}
