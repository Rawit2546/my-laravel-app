<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_name',
        'task_name',
        'contract_number',
        'department',
        'supervisor',
        'start_date',
        'end_date',
        'time',
        'status',
        'details',
        'name',
        'location',
    ];
}