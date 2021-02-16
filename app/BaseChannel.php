<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseChannel extends Model 
{
    protected $table = 'base_channels';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];
}
