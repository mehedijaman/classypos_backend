<?php

namespace ClassyPOS\Models\Accounts\Income;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomeCategory extends Model
{
    use SoftDeletes, UsesTenantConnection;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table = 'income_categories';

    protected $fillable = [
        'Name',
        'Color',
        'Status'
    ];

}
