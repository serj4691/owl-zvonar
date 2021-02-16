<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BasePhoneSet extends Model
{
    protected $table = 'base_phone_sets';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'phone',
        'complexes',
        'sources',
        'channels',
        'first_contact_date_from',
        'first_contact_date_to',
        'success_calls_from',
        'success_calls_to',
        'got_through_calls_from',
        'got_through_calls_to',
        'failed_calls_from',
        'failed_calls_to',
    ];

    /**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
    public function complexes()
	{
		return $this->belongsToMany(BaseComplex::class, 'base_phone_set_base_complex')->withPivot('base_phone_set_id');
	}

}
