<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBenefit extends Model
{

    protected $guarded = [
        'employee_id',
        'id'
    ];
}
