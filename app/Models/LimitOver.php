<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Area
 */
class LimitOver extends Model
{
    protected $table = 'limit_over';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'limit_over_max',
        'limit_over_max_hp',
        'limit_over_max_atk',
        'limit_over_max_cost',
        'limit_over_max_charm',
        'limit_grow'
    ];

    protected $guarded = [];

}
