<?php

namespace ClassyPOS\Models\Employees;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use SoftDeletes, usesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "employees";

    protected $fillable = [
        'ShopID',
        'Name',
        'Code',
        'DateOfBirth',
        'MaritalStatus',
        'Gender',
        'Qualification',
        'Address',
        'Phone',
        'Email',
        'JoiningDate',
        'TerminationDate',
        'BloodGroup',
        'BankName',
        'BranchName',
        'AccountNumber'
    ];
}
