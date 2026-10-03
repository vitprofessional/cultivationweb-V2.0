<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class newAdmission extends Model
{
    public function classInfo(): BelongsTo
    {
        return $this->belongsTo(classManage::class, 'className', 'id');
    }

    public function scopeWithValidClass(Builder $query): Builder
    {
        return $query->whereHas('classInfo', function (Builder $class): void {
            $class->whereNotNull('className')
                ->whereRaw("TRIM(className) <> ''")
                ->whereRaw("LOWER(TRIM(className)) <> ?", ['no class']);
        });
    }

    public function getStudentNameAttribute(): string
    {
        $fullName = preg_replace('/\s+/', ' ', trim((string) ($this->attributes['fullName'] ?? '')));
        $sureName = preg_replace('/\s+/', ' ', trim((string) ($this->attributes['sureName'] ?? '')));

        if ($fullName === '') {
            return $sureName !== '' ? $sureName : 'N/A';
        }

        if ($sureName !== '' && ! preg_match('/(?:^|\s)' . preg_quote($sureName, '/') . '$/iu', $fullName)) {
            return $fullName . ' ' . $sureName;
        }

        return $fullName;
    }
}
