<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentManagement extends Model
{
    use HasFactory;

    // Allow mass assignment (we still set attributes explicitly in controller)
    protected $guarded = [];
}
