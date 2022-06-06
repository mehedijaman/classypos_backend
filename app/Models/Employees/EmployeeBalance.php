<?php

namespace ClassyPOS\Models\Employees;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeBalance extends Model
{
    use SoftDeletes, usesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "employee_balances";

    protected $fillable = [
        'EmployeeID',
        'Balance'
    ];
}
