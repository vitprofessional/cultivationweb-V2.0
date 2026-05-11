<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StaffManagement extends Model
{
    public static function getDesignationName($designation) {
        if (empty($designation) && $designation !== '0') {
            return 'Teacher';
        }

        if (!is_numeric($designation)) {
            return trim($designation);
        }

        // Try to fetch designation name from `designations` DB table first
        try {
            $dbName = DB::table('designations')->where('id', (int)$designation)->value('name');
            if (!empty($dbName)) return $dbName;
        } catch (\Exception $e) {
            // ignore DB errors and fall back to hardcoded list
        }

        $designations = [
            1 => 'Administrative Officer',
            2 => 'Office Assistant-cum-Computer Operator',
            3 => 'Accounts Assistant',
            4 => 'Office Assistant',
            5 => 'Registrar',
            6 => 'Librarian',
            7 => 'Assistant Librarian',
            8 => 'IT Officer / System Admin / ICT Technician', // Fixed duplicate value 7
            9 => 'Data Entry Operator', // Fixed from value 8
            10 => 'Lab Assistant / Lab Attendant', // Fixed from value 9
            11 => 'Sports Instructor / Coach', // Fixed from value 10
            12 => 'Music Teacher / Art Teacher', // Fixed from value 11
            13 => 'Hostel Superintendent / Hostel Warden', // Fixed from value 12
            14 => 'Office Peon / Office Assistant', // Fixed from value 13
            15 => 'MLSS', // Fixed from value 14
            16 => 'Security Guard', // Fixed from value 15
            17 => 'Gatekeeper', // Fixed from value 16
            18 => 'Gardener', // Fixed from value 17
            19 => 'Cleaner / Ayah', // Fixed from value 18
            20 => 'Driver' // Fixed from value 19        
        ];
        
        return $designations[$designation] ?? 'Teacher';
    }
    use HasFactory;
}
