<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Present
 */
class Present extends Model
{
    protected $table = 'present';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'present_type',
        'present_param1',
        'present_param2',
        'present_param3',
        'present_param4',
        'present_param5',
        'present_param6',
        'present_param7',
        'present_param8',
        'present_param9',
        'present_param10'
    ];

    protected $guarded = [];


}
