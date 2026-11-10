<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Minimal read-only mirror of the admin app's Exam (shared `exams` table).
 * One row per class-specific exam; rows sharing exam_type_id + session_id form an "exam group".
 */
class Exam extends Model
{
    public function type(): BelongsTo
    {
        return $this->belongsTo(ExamType::class, 'exam_type_id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(sessionManage::class, 'session_id');
    }

    public function classInfo(): BelongsTo
    {
        return $this->belongsTo(classManage::class, 'className', 'id');
    }
}
