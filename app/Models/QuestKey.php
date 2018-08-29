<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Quest
 */
class QuestKey extends Model
{
    protected $table = 'quest_key';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'key_name',
        'key_area_id',
        'timing_end'
    ];

    protected $guarded = [];

}
