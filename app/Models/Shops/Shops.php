<?php

namespace ClassyPOS\Models\Shops;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shops extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "shops";

    protected $fillable = [
        'Name',
        'LegalName',
        'Type',
        'Address',
        'City',
        'Province',
        'Phone',
        'Email',
        'Website',
        'Logo',
        'LicenceNo',
        'VatRegNo',
        'Notes'
    ];
}
