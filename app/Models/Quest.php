<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Quest
 */
class Quest extends Model
{
    protected $table = 'quest';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'area_id',
        'quest_name',
        'difficulty_name',
        'story',
        'quest_stamina',
        'quest_ticket',
        'quest_key',
        'quest_floor_bonus_type',
        'clear_money',
        'clear_exp',
        'clear_link_point',
        'clear_stone',
        'item_money',
        'clear_unit',
        'clear_unit_lv',
        'clear_unit_msg',
        'clear_item',
        'clear_item_num',
        'clear_item_msg',
        'clear_key',
        'clear_key_num',
        'clear_key_msg',
        'once',
        'battle_chain',
        'quest_requirement_id',
        'quest_requirement_text',
        'enable_continue',
        'enable_retry',
        'enable_friendpoint',
        'voice_group_id',
        'movie_name',
        'packname_voice',
        'boss_ability_1',
        'boss_ability_2',
        'boss_ability_3',
        'boss_ability_4',
        'boss_chara_id',
        'e_chara_id_0',
        'e_chara_id_1',
        'e_chara_id_2',
        'e_chara_id_3',
        'e_chara_id_4',
        'floor_count'
    ];

    protected $guarded = [];

    public function area(){ return $this->hasOne('App\Models\Area', 'fix_id', 'area_id')->first(); }
    public function questRequirement(){ return $this->hasOne('App\Models\QuestRequirement', 'fix_id', 'quest_requirement_id')->first(); }
    public function clearUnit(){ return $this->hasOne('App\Models\Unit', 'fix_id', 'clear_unit')->first(); }
    public function boss(){ return $this->hasOne('App\Models\Unit', 'fix_id', 'boss_chara_id')->first(); }
    public function eChara($id){ return $this->hasOne('App\Models\Unit', 'fix_id', 'e_chara_id_'.$id)->first(); }
    public function bossAbility($id){ return $this->hasOne('App\Models\EnemyAbility', 'fix_id', 'boss_ability_'.$id)->first(); }

    public function getTranslate(){
        return Translate::where([
            ['type','=','1'],
            ['id','=',$this->fix_id],
        ])->first();
    }
}
