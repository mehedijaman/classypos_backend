<?php

namespace ClassyPOS\Models\Contacts;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactBalance extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "contact_balances";

    protected $fillable = [
        'ContactID',
        'Balance',
    ];
}
