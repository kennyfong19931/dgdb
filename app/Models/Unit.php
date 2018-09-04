<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Unit
 */
class Unit extends Model
{
    protected $table = 'unit';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'name',
        'detail',
        'draw_id',
        'rare',
        'rarity',
        'res_chara_id_str',
        'element',
        'kind',
        'sub_kind',
        'skill_leader',
        'skill_limitbreak',
        'skill_passive',
        'skill_active0',
        'skill_active1',
        'link_enable',
        'link_skill_active',
        'link_skill_passive',
        'link_unit_id_parts1',
        'link_unit_id_parts2',
        'link_unit_id_parts3',
        'link_money',
        'link_del_unit_id_parts1',
        'link_del_unit_id_parts2',
        'link_del_unit_id_parts3',
        'link_del_money',
        'party_cost',
        'level_min',
        'level_max',
        'limit_over_type',
        'limit_over_value',
        'limit_over_synthesis_type',
        'limit_over_attribute',
        'limit_over_unitpoint',
        'evol_unitpoint',
        'exp_total',
        'exp_total_curve',
        'base_hp_min',
        'base_hp_max',
        'base_hp_curve',
        'base_attack_min',
        'base_attack_max',
        'base_attack_curve',
        'base_defense_min',
        'base_defense_max',
        'base_defense_curve',
        'blend_exp_min',
        'blend_exp_max',
        'blend_exp_curve',
        'skill_plus',
        'skill_plus_element',
        'sales_min',
        'sales_max',
        'sales_curve',
        'material_link_point',
        'sales_unitpoint',
        'wild_egg_flg',
        'series',
        'size'
    ];

    protected $guarded = [];

    public function getSkill($type){
        switch($type){
            case 'ls':
                return $this->hasOne('App\Models\LSkill', 'fix_id', 'skill_leader')->first();
                break;
            case 'as':
                return $this->hasOne('App\Models\ASkill', 'fix_id', 'skill_limitbreak')->first();
                break;
            case 'ns1':
                return $this->hasOne('App\Models\NSkill', 'fix_id', 'skill_active0')->first();
                break;
            case 'ns2':
                return $this->hasOne('App\Models\NSkill', 'fix_id', 'skill_active1')->first();
                break;
            case 'ps':
                return $this->hasOne('App\Models\PSkill', 'fix_id', 'skill_passive')->first();
                break;
            case 'lns':
                return $this->hasOne('App\Models\NSkill', 'fix_id', 'link_skill_active')->first();
                break;
            case 'lps':
                return $this->hasOne('App\Models\PSkill', 'fix_id', 'link_skill_passive')->first();
                break;

        }
    }

    public function linkPart1(){
        return $this->hasOne('App\Models\Unit', 'fix_id', 'link_unit_id_parts1')->first();
    }

    public function linkPart2(){
        return $this->hasOne('App\Models\Unit', 'fix_id', 'link_unit_id_parts2')->first();
    }

    public function linkPart3(){
        return $this->hasOne('App\Models\Unit', 'fix_id', 'link_unit_id_parts3')->first();
    }

    public function delLinkPart1(){
        return $this->hasOne('App\Models\Unit', 'fix_id', 'link_del_unit_id_parts1')->first();
    }

    public function delLinkPart2(){
        return $this->hasOne('App\Models\Unit', 'fix_id', 'link_del_unit_id_parts2')->first();
    }

    public function delLinkPart3(){
        return $this->hasOne('App\Models\Unit', 'fix_id', 'link_del_unit_id_parts3')->first();
    }

    public function limitOver(){
        return $this->hasOne('App\Models\LimitOver', 'fix_id' ,'limit_over_type')->first();
    }

    public function getTranslate(){
        return Translate::where([
            ['type','=','2'],
            ['id','=',$this->draw_id],
        ])->first();
    }

    public function getApiObj(){
        $output = [];
        $output['draw_id'] = $this->draw_id;
        $output['name'] = $this->name;
        return $output;
    }
}
