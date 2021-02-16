<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseSource extends Model
{
     protected $table = 'base_sources';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];
}
