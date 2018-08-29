<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class QuestRequirement
 */
class QuestRequirement extends Model
{
    protected $table = 'quest_requirement';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'elem_fire',
        'elem_water',
        'elem_wind',
        'elem_light',
        'elem_dark',
        'elem_naught',
        'kind_human',
        'kind_fairy',
        'kind_demon',
        'kind_dragon',
        'kind_machine',
        'kind_beast',
        'kind_god',
        'kind_egg',
        'num_elem',
        'num_kind',
        'num_unit',
        'much_name',
        'require_unit_00',
        'require_unit_01',
        'require_unit_02',
        'require_unit_03',
        'require_unit_04',
        'limit_rare',
        'limit_cost',
        'limit_cost_total',
        'limit_unit_lv',
        'limit_unit_lv_total',
        'limit_rank',
        'rule_disable_as',
        'rule_disable_ls',
        'rule_heal_half',
        'rule_disable_affinity',
        'fix_unit_00_enable',
        'fix_unit_00_id',
        'fix_unit_00_lv',
        'fix_unit_00_lv_lbs',
        'fix_unit_00_plus_hp',
        'fix_unit_00_plus_atk',
        'fix_unit_00_link_enable',
        'fix_unit_00_link_id',
        'fix_unit_00_link_lv',
        'fix_unit_00_link_plus_hp',
        'fix_unit_00_link_plus_atk',
        'fix_unit_00_link_point',
        'fix_unit_01_enable',
        'fix_unit_01_id',
        'fix_unit_01_lv',
        'fix_unit_01_lv_lbs',
        'fix_unit_01_plus_hp',
        'fix_unit_01_plus_atk',
        'fix_unit_01_link_enable',
        'fix_unit_01_link_id',
        'fix_unit_01_link_lv',
        'fix_unit_01_link_plus_hp',
        'fix_unit_01_link_plus_atk',
        'fix_unit_01_link_point',
        'fix_unit_02_enable',
        'fix_unit_02_id',
        'fix_unit_02_lv',
        'fix_unit_02_lv_lbs',
        'fix_unit_02_plus_hp',
        'fix_unit_02_plus_atk',
        'fix_unit_02_link_enable',
        'fix_unit_02_link_id',
        'fix_unit_02_link_lv',
        'fix_unit_02_link_plus_hp',
        'fix_unit_02_link_plus_atk',
        'fix_unit_02_link_point',
        'fix_unit_03_enable',
        'fix_unit_03_id',
        'fix_unit_03_lv',
        'fix_unit_03_lv_lbs',
        'fix_unit_03_plus_hp',
        'fix_unit_03_plus_atk',
        'fix_unit_03_link_enable',
        'fix_unit_03_link_id',
        'fix_unit_03_link_lv',
        'fix_unit_03_link_plus_hp',
        'fix_unit_03_link_plus_atk',
        'fix_unit_03_link_point',
        'fix_unit_04_enable',
        'fix_unit_04_id',
        'fix_unit_04_lv',
        'fix_unit_04_lv_lbs',
        'fix_unit_04_plus_hp',
        'fix_unit_04_plus_atk',
        'fix_unit_04_link_enable',
        'fix_unit_04_link_id',
        'fix_unit_04_link_lv',
        'fix_unit_04_link_plus_hp',
        'fix_unit_04_link_plus_atk',
        'fix_unit_04_link_point'
    ];

    protected $guarded = [];

    public function require_unit($id){ return $this->hasOne('App\Models\Unit', 'fix_id', 'require_unit_0'.$id)->first(); }
    public function fix_unit($id){ return $this->hasOne('App\Models\Unit', 'fix_id', 'fix_unit_0'.$id.'_id')->first(); }
    public function link_unit($id){ return $this->hasOne('App\Models\Unit', 'fix_id', 'fix_unit_0'.$id.'_link_id')->first(); }
}
