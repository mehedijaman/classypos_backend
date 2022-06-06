<?php

namespace ClassyPOS\Models\Accounts\Expense;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseCategory extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "expense_categories";

    protected $fillable = [
        'Name',
        'Color',
        'Status'
    ];
}
