<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LoginTotal
 */
class LoginTotal extends Model
{
    protected $table = 'login_total';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'login_ct',
        'acquire_money',
        'acquire_fp',
        'acquire_stone',
        'acquire_unit_id'
    ];

    protected $guarded = [];


}
