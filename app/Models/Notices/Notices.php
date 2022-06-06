<?php

namespace ClassyPOS\Models\Notices;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notices extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = 'notices';

    protected $fillable = [
        'ShopID',
        'UserID',
        'ToUserID',
        'ShowDate',
        'Title',
        'Message',
        'IsDismissed'
    ];
}
