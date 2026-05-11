<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marksheet extends Model
{
    use HasFactory;

    // Map to the correct table name
    protected $table = 'internal_results';

    // Allow mass-assignment for common fields (adjust as needed)
    protected $fillable = [
        'title',
        'assignClass',
        'assignSection',
        'assignDepartment',
        'assignSession',
        'attachment',
    ];
}
