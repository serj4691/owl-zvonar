<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BasePhone extends Model
{
    protected $table = 'base_phones';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'phone',
        'complexes',
        'sources',
        'channels',
        'first_contact_date',
        'comments',
        'success_calls',
        'got_through_calls',
        'failed_calls',
    ];

    /**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
    public function complexes()
	{
		return $this->belongsToMany('\App\BaseComplex', 'base_phone_base_complex', 'base_phone_id', 'base_complex_id' );
	}
    // public function getAttribute()
    // {
    //     return $this->belongsToMany('\App\BaseComplex', 'base_phone_base_complex', 'base_phone_id', 'base_complex_id' );
    // }
}
