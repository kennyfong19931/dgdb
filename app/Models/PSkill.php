<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Util\FunctionUtil;
use \DB;

/**
 * Class PSkill
 */
class PSkill extends Model
{
    protected $table = 'p_skill';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'name',
        'detail',
        'detailcn',
        'skill_trap_pass_active',
        'skill_trap_pass_type',
        'skill_powup_kind_active',
        'skill_powup_kind_type',
        'skill_powup_kind_status',
        'skill_powup_kind_rate',
        'skill_counter_atk_active',
        'skill_counter_atk_element',
        'skill_counter_atk_odds',
        'skill_counter_atk_scale',
        'skill_counter_atk_effect',
        'skill_damage_recovery_active',
        'skill_damage_recovery_odds',
        'skill_damage_recovery_rate',
        'skill_hp_full_powup_active',
        'skill_hp_full_powup_scale',
        'skill_dying_powup_active',
        'skill_dying_powup_border',
        'skill_dying_powup_scale',
        'skill_backatk_pass_active',
        'skill_backatk_pass_rate',
        'skill_decline_dmg_elem_active',
        'skill_decline_dmg_elem_elem',
        'skill_decline_dmg_elem_rate',
        'skill_decline_dmg_kind_active',
        'skill_decline_dmg_kind_kind',
        'skill_decline_dmg_kind_rate',
        'skill_boost_chance_active',
        'skill_boost_chance_count',
        'skill_type',
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

    public function getDetailCn(){
        $text = '';
        $function = new FunctionUtil();
        $text .= $function->getSkillDetailCn('p', $this);
        return $text;
    }

    public function units(){return $this->hasMany('App\Models\Unit', 'skill_passive', 'fix_id')->get(); }
    public function linkUnits(){return $this->hasMany('App\Models\Unit', 'link_skill_passive', 'fix_id')->get(); }
    public function minUnits(){ return DB::table('unit')->select('fix_id','name','draw_id')->where('skill_passive','=',$this->fix_id)->orderBy('draw_id')->get(); }
    public function minLinkUnits(){ return DB::table('unit')->select('fix_id','name','draw_id')->where('link_skill_passive','=',$this->fix_id)->orderBy('draw_id')->get(); }
}
