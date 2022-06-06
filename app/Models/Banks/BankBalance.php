<?php

namespace ClassyPOS\Models\Banks;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankBalance extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $times = ['deleted_at'];

    protected $table = 'bank_balances';

    protected $fillable = [
        'BankID',
        'Balance',
    ];
}
