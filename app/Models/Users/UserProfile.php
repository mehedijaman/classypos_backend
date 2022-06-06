<?php

namespace ClassyPOS\Models\Users;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfile extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "user_profiles";

    protected $fillable = [
        'ShopID',
        'Phone',
        'FirstName',
        'LastName',
        'Address',
        'City',
        'Province',
        'ZipCode',
        'Country',
        'DateOfBirth',
        'Image',
        'LastLogin',
    ];
}
