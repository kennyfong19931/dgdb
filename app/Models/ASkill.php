<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Util\FunctionUtil;
use \DB;

/**
 * Class ASkill
 */
class ASkill extends Model
{
    protected $table = 'a_skill';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'name',
        'detail',
        'detailcn',
        'use_turn',
        'use_sp',
        'level_max',
        'level_up_rate',
        'phase',
        'subject_type',
        'subject_value',
        'skill_elem',
        'skill_type',
        'skill_cate',
        'skill_effect',
        'skill_damage_enable',
        'skill_power',
        'skill_power_fix',
        'skill_power_hp_rate',
        'skill_absorb',
        'skill_kickback',
        'skill_kickback_fix',
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
        'value0',
        'value1',
        'value2',
        'value3',
        'value4',
        'value5',
        'value6',
        'value7',
        'value8',
        'value9',
        'value10',
        'value11',
        'value12',
        'value13',
        'value14',
        'value15'
    ];

    protected $guarded = [];

    public function getDetailCn(){
        $text = '';
        $function = new FunctionUtil();
        $text .= $function->getSkillDetailCn('a', $this);
        return $text;
    }

    public function getStatusAilment($num){ return $this->hasOne('App\Models\StatusAilment', 'fix_id', 'status_ailment'.$num)->first(); }
    public function units(){return $this->hasMany('App\Models\Unit', 'skill_limitbreak', 'fix_id')->get(); }
    public function minUnits(){ return DB::table('unit')->select('fix_id','name','draw_id')->where('skill_limitbreak','=',$this->fix_id)->orderBy('draw_id')->get(); }
}
