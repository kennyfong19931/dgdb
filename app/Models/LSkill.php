<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Util\FunctionUtil;
use \DB;

/**
 * Class LSkill
 */
class LSkill extends Model
{
    protected $table = 'l_skill';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'name',
        'detail',
        'detailcn',
        'add_fix_id',
        'skill_powup_elem_active',
        'skill_powup_elem_type',
        'skill_powup_elem_status',
        'skill_powup_elem_rate',
        'skill_powup_kind_active',
        'skill_powup_kind_type',
        'skill_powup_kind_status',
        'skill_powup_kind_rate',
        'skill_follow_atk_active',
        'skill_follow_atk_element',
        'skill_follow_atk_rate',
        'skill_follow_atk_effect',
        'skill_decline_dmg_active',
        'skill_decline_dmg_element',
        'skill_decline_dmg_rate',
        'skill_recovery_move_active',
        'skill_recovery_move_rate',
        'skill_recovery_battle_active',
        'skill_recovery_battle_rate',
        'skill_quick_time_active',
        'skill_quick_time_second',
        'skill_recovery_support_active',
        'skill_recovery_support_rate',
        'skill_recovery_atk_active',
        'skill_recovery_atk_rate',
        'skill_hpfull_powup_active',
        'skill_hpfull_powup_rate',
        'skill_hpdown_powup_active',
        'skill_hpdown_powup_border',
        'skill_hpdown_powup_rate',
        'skill_mekuri_powup_active',
        'skill_funbari_active',
        'skill_funbari_border',
        'skill_hpfull_guard_active',
        'skill_hpfull_guard_rate',
        'skill_initiative_atk_active',
        'skill_initiative_atk_b_0',
        'skill_initiative_atk_b_1',
        'skill_initiative_atk_b_2',
        'skill_initiative_atk_y_0',
        'skill_initiative_atk_y_1',
        'skill_initiative_atk_y_2',
        'skill_initiative_atk_r_0',
        'skill_initiative_atk_r_1',
        'skill_initiative_atk_r_2',
        'skill_transform_card_active',
        'skill_transform_card_root',
        'skill_transform_card_dest',
        'skill_damageup_color_active',
        'skill_damageup_color_count',
        'skill_damageup_color_rate',
        'skill_damageup_hands_active',
        'skill_damageup_hands_count',
        'skill_damageup_hands_rate',
        'skill_type',
        'skill_value_00',
        'skill_value_01',
        'skill_value_02',
        'skill_value_03',
        'skill_value_04',
        'skill_value_05',
        'skill_value_06',
        'skill_value_07',
        'skill_value_08',
        'skill_value_09',
        'skill_value_10',
        'skill_value_11',
        'skill_value_12',
        'skill_value_13',
        'skill_value_14',
        'skill_value_15'
    ];

    protected $guarded = [];

    public function getAddFixId(){ return $this->hasOne('App\Models\LSkill', 'fix_id', 'add_fix_id'); }

    public function getDetailCn(){
        $text = '';
        $function = new FunctionUtil();
        $text .= $function->getSkillDetailCn('l', $this);
        if($this->add_fix_id != 0){
            $text .= '。'.$function->getSkillDetailCn('l', $this->getAddFixId);
        }
        return $text;
    }

    public function units(){return $this->hasMany('App\Models\Unit', 'skill_leader', 'fix_id')->get(); }
    public function minUnits(){ return DB::table('unit')->select('name','draw_id')->where('skill_leader','=',$this->fix_id)->orderBy('draw_id')->get(); }
}
