<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerPhone extends Model
{
    protected $table = 'customer_phones';
    protected $primaryKey = 'id';

    protected $fillable = [
        'phoneNumber',
        'nameSource',
        'nameBase',
        'UTM_campaign',
        'UTM_Source',
        'nameResidentialComplexDonor'
    ];
}
