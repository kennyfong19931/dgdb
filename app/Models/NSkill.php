<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Util\FunctionUtil;
use App\Models\Unit;
use \DB;

/**
 * Class NSkill
 */
class NSkill extends Model
{
    protected $table = 'n_skill';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'name',
        'detail',
        'detailcn',
        'boostcn',
        'always',
        'skill_element',
        'cost1',
        'cost2',
        'cost3',
        'cost4',
        'cost5',
        'skill_type',
        'skill_value',
        'skill_value_rand',
        'skill_poison_active',
        'skill_poison_turn_min',
        'skill_poison_turn_max',
        'skill_poison_scale',
        'skill_coerce_active',
        'skill_coerce_turn_min',
        'skill_coerce_turn_max',
        'skill_guard_active',
        'skill_guard_turn_min',
        'skill_guard_turn_max',
        'skill_guard_rate',
        'skill_week_active',
        'skill_week_turn_min',
        'skill_week_turn_max',
        'skill_week_rate',
        'skill_change_active',
        'skill_change_elem_prev',
        'skill_change_elem_after',
        'skill_ct_add_active',
        'skill_ct_add_second',
        'skill_drain_active',
        'skill_drain_scale',
        'skill_critical_odds',
        'skill_boost_name',
        'skill_boost_element',
        'skill_boost_effect',
        'skill_boost_id',
        'skill_link_name',
        'skill_link_detail',
        'skill_link_odds',
        'ability',
        'ability_effect',
        'ability_type',
        'ability_value',
        'ability_value_rand',
        'ability_critical',
        'param_00',
        'param_01',
        'param_02',
        'param_03',
        'param_04',
        'param_05',
        'param_06',
        'param_07'
    ];

    protected $guarded = [];

    public function getDetailCn(){
        $text = '';
        $function = new FunctionUtil();
        $text .= $function->getSkillDetailCn('n', $this);
        return $text;
    }

    public function getSkillBoost(){ return $this->hasOne('App\Models\SkillBoost', 'fix_id', 'skill_boost_id'); }
    public function getCard(){ return array($this->cost1, $this->cost2, $this->cost3, $this->cost4, $this->cost5); }
    public function units(){ return Unit::where('skill_active0','=',$this->fix_id)->orWhere('skill_active1','=',$this->fix_id)->get(); }
    public function linkUnits(){ return Unit::where('link_skill_active','=',$this->fix_id)->get(); }
    public function minUnits(){ return DB::table('unit')->select('fix_id','name','draw_id')->where('skill_active0','=',$this->fix_id)->orWhere('skill_active1','=',$this->fix_id)->orderBy('draw_id')->get(); }
    public function minLinkUnits(){ return DB::table('unit')->select('fix_id','name','draw_id')->where('link_skill_active','=',$this->fix_id)->orderBy('draw_id')->get(); }
}
