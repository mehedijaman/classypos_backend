<?php

namespace ClassyPOS\Models\Taxes;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taxes extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "taxes";

    protected $fillable = [
        'Name',
        'Percent',
        'Status',
    ];
}
