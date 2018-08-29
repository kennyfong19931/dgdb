<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class StatusAilment
 */
class StatusAilment extends Model
{
    protected $table = 'status_ailment';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'good_or_bad',
        'category',
        'duration',
        'icon',
        'name',
        'detail',
        'update_move',
        'update_battle',
        'param01',
        'param02',
        'param03',
        'param04',
        'param05',
        'param06',
        'param07',
        'param08',
        'param09',
        'param10',
        'param11',
        'param12'
    ];

    protected $guarded = [];


}
