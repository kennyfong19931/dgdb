<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Achievement
 */
class Achievement extends Model
{
    protected $table = 'achievement';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'draw_id',
        'category',
        'draw_msg',
        'event_id',
        'timing_start',
        'achievement_type',
        'achievement_param_1',
        'achievement_param_2',
        'achievement_param_3',
        'achievement_param_4',
        'achievement_param_5',
        'achievement_param_6',
        'achievement_param_7',
        'achievement_param_8',
        'achievement_param_9',
        'achievement_param_10',
        'open_type',
        'open_param1',
        'quest_id',
        'server_state',
        'server_time_open',
        'present_ids'
    ];

    protected $guarded = [];


}
