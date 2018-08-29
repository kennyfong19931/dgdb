<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EnemyActionTable
 */
class EnemyActionTable extends Model
{
    protected $table = 'enemy_action_table';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'change_msg',
        'action_select_type',
        'action_param_id1',
        'action_param_id2',
        'action_param_id3',
        'action_param_id4',
        'action_param_id5',
        'action_param_id6',
        'action_param_id7',
        'action_param_id8',
        'timing_priority',
        'timing_type',
        'timing_param1',
        'timing_param2',
        'timing_param3',
        'timing_param4',
        'timing_param5',
        'timing_param6',
        'timing_param7',
        'timing_param8'
    ];

    protected $guarded = [];

    public function actionParam($id){ return $this->hasOne('App\Models\EnemyActionParam', 'fix_id', 'action_param_id'.$id)->first(); }
}
