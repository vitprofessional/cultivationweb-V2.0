<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TeacherManagement extends Model
{
    use HasFactory;
    public static function getDesignationName($designation) {
        // If designation is empty, return default
        if (empty($designation) && $designation !== '0') {
            return 'Teacher';
        }

        // If the stored value is already a text name, return it directly
        if (!is_numeric($designation)) {
            return trim($designation);
        }

        // Try to get designation name from `designations` DB table first (if present)
        try {
            $dbName = DB::table('designations')->where('id', (int)$designation)->value('name');
            if (!empty($dbName)) return $dbName;
        } catch (\Exception $e) {
            // ignore DB errors and fall back to hardcoded list
        }

        $designations = [
            1 => 'Principal',
            2 => 'Principal(Incharge)',
            3 => 'Vice Principal',
            4 => 'Head Master',
            5 => 'Head Master(Incharge)',
            6 => 'Assistant Head Master',
            7 => 'Senior Teacher',
            8 => 'Assistant Teacher',
            9 => 'Muallim',
            10 => 'Assistant Muallim',
            11 => 'Lecturer (Fazil/Kamil)',
            12 => 'Hafiz & Hafezia Instructor',
            13 => 'Arabic Teacher',
            14 => 'Quran Teacher',
            15 => 'Hadith Teacher'
        ];
        
        return $designations[$designation] ?? 'Teacher';
    }
}
