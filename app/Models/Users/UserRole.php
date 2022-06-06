<?php

namespace ClassyPOS\Models\Users;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "user_roles";

    protected $fillable = [
        'RoleCategoryID',
        'UserID'
    ];
}
