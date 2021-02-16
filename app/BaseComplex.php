<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseComplex extends Model
{
    protected $table = 'base_complexes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    /**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
    public function phones()
	{
		return $this->belongsToMany('\App\BasePhone', 'base_phone_base_complex', 'base_complex_id', 'base_phone_id');
	}

}
