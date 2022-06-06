<?php

namespace ClassyPOS\Models\Company;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Company extends Model
{
    use softDeletes, UsesTenantConnection;

    protected $times = ['deleted_at'];

    protected $table = 'banks';

    protected $fillable = [
        'Name',
        'Address',
        'Phone',
        'Email',
        'Website',
        'Country',
        'FinancialYearFrom',
        'BooksBeginingFrom',
        'TIN',
        'Logo',
        'CurrentDate',
    ];
}
