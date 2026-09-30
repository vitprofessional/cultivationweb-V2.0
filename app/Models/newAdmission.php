<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class newAdmission extends Model
{
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
