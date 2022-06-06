<?php

namespace ClassyPOS\Models\Reviews;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\{
    Model, SoftDeletes
};

class Reviews extends Model
{
    use SoftDeletes, UsesTenantConnection;

    protected $dates = ['deleted_at'];

    protected $table = 'reviews';

    protected $fillable = [
        'Name',
        'Email',
        'Phone',
        'Rating',
        'Reaction',
        'Notes',
    ];
}
