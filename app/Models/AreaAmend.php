<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AreaAmend
 */
class AreaAmend extends Model
{
    protected $table = 'area_amend';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'active',
        'timing_start',
        'timing_end',
        'user_group',
        'area_id',
        'amend',
        'amend_value'
    ];

    protected $guarded = [];


}
