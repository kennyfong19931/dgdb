<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class QuestFloor
 */
class QuestFloor extends Model
{
    protected $table = 'quest_floor';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'quest_id',
        'under',
        'boss_group_id',
        'evol_direct_type',
        'pattern_expect',
        'pattern_category',
        'question_ct',
        'enemy_group_id_1',
        'enemy_group_id_2',
        'enemy_group_id_3',
        'enemy_group_id_4',
        'enemy_group_id_5',
        'enemy_group_id_6',
        'enemy_group_id_7',
        'trap_group_id_1',
        'trap_group_id_2',
        'trap_group_id_3',
        'trap_group_id_4',
        'trap_group_id_5',
        'trap_group_id_6',
        'trap_group_id_7',
        'item_group_id_1',
        'item_group_id_2',
        'item_group_id_3',
        'item_group_id_4',
        'item_group_id_5',
        'item_group_id_6',
        'item_group_id_7',
        'heal_group_id_1',
        'heal_group_id_2',
        'heal_group_id_3',
        'heal_group_id_4',
        'heal_group_id_5',
        'heal_group_id_6',
        'heal_group_id_7'
    ];

    protected $guarded = [];


    public function bossGroup(){
        return $this->hasOne('App\Models\EnemyGroup', 'fix_id', 'boss_group_id')->first();
    }
    public function enemygroup($id){
        return $this->hasOne('App\Models\EnemyGroup', 'fix_id', 'enemy_group_id_'.$id)->first();
    }
    public function trapgroup($id){
        return $this->hasOne('App\Models\PanelGroup', 'fix_id', 'trap_group_id_'.$id)->first();
    }
    public function itemgroup($id){
        return $this->hasOne('App\Models\PanelGroup', 'fix_id', 'item_group_id_'.$id)->first();
    }
    public function healgroup($id){
        return $this->hasOne('App\Models\PanelGroup', 'fix_id', 'heal_group_id_'.$id)->first();
    }
}
