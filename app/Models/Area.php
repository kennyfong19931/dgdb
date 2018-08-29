<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Area
 */
class Area extends Model
{
    protected $table = 'area';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'view_id',
        'area_name',
        'area_name_eng',
        'area_detail',
        'area_cate_id',
        'questlist_sort',
        'event_id',
        'type',
        'pre_area',
        'cost0',
        'cost1',
        'cost2',
        'cost3',
        'cost4',
        'cost5',
        'cost6',
        'area_element',
        'res_map',
        'res_map_icon',
        'res_icon_key',
        'res_icon_box',
        'packname_se',
        'packname_bgm',
        'area_url'
    ];

    protected $guarded = [];

    public function areaCate(){ return $this->hasOne('App\Models\AreaCategory', 'fix_id', 'area_cate_id')->first(); }
    public function areaKey(){ return $this->hasOne('App\Models\QuestKey', 'key_area_id', 'fix_id')->first(); }

    public function getBoss(){
        $quests = $this->hasMany('App\Models\Quest', 'area_id', 'fix_id')->get()->reverse();
        foreach($quests as $quest){
            if($quest->quest_name != 'reserve'){
                return $quest->boss();
            }
        }
    }
}
