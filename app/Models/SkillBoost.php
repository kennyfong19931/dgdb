<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SkillBoost
 */
class SkillBoost extends Model
{
    protected $table = 'skill_boost';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'skill_type',
        'skill_cate',
        'skill_damage_enable',
        'skill_power',
        'skill_power_fix',
        'skill_power_hp_rate',
        'skill_absorb',
        'skill_chk_atk_affinity',
        'skill_chk_atk_leader',
        'skill_chk_atk_passive',
        'skill_chk_atk_ailment',
        'skill_chk_def_defence',
        'skill_chk_def_ailment',
        'status_ailment_target',
        'status_ailment1',
        'status_ailment2',
        'status_ailment3',
        'status_ailment4',
        'skill_param_00',
        'skill_param_01',
        'skill_param_02',
        'skill_param_03',
        'skill_param_04',
        'skill_param_05',
        'skill_param_06',
        'skill_param_07',
        'skill_param_08',
        'skill_param_09',
        'skill_param_10',
        'skill_param_11',
        'skill_param_12',
        'skill_param_13',
        'skill_param_14',
        'skill_param_15'
    ];

    protected $guarded = [];

    public function getStatusAilment($num){ return $this->hasOne('App\Models\StatusAilment', 'fix_id', 'status_ailment'.$num)->first(); }
    public function getNSkill(){ return $this->hasOne('App\Models\NSkill', 'skill_boost_id', 'fix_id')->first(); }
}
