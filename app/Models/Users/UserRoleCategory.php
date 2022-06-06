<?php
namespace ClassyPOS\Models\Users;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class UserRoleCategory extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = "user_role_categories";

    protected $fillable = ['RoleCategoryName'];
}
