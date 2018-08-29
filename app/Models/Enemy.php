<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Enemy
 */
class Enemy extends Model
{
    protected $table = 'enemy';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'chara_id',
        'status_hp',
        'status_pow',
        'status_def',
        'status_turn',
        'acquire_money',
        'acquire_exp',
        'drop_unit_id',
        'drop_unit_level',
        'drop_unit_rate',
        'drop_plus_pow',
        'drop_plus_hp',
        'drop_plus_none',
        'drop_money_value',
        'drop_money_rate',
        'act_table1',
        'act_table2',
        'act_table3',
        'act_table4',
        'act_table5',
        'act_table6',
        'act_table7',
        'act_table8',
        'act_first',
        'act_dead',
        'ability1',
        'ability2',
        'ability3',
        'ability4'
    ];

    protected $guarded = [];

    public function unit(){ return $this->hasOne('App\Models\Unit', 'fix_id', 'chara_id')->first(); }
    public function dropUnit(){ return $this->hasOne('App\Models\Unit', 'fix_id', 'drop_unit_id')->first(); }
    public function actFirst(){ return $this->hasOne('App\Models\EnemyActionParam', 'fix_id', 'act_first')->first(); }
    public function actDead(){ return $this->hasOne('App\Models\EnemyActionParam', 'fix_id', 'act_dead')->first(); }
    public function actTable($id){
        return $this->hasOne('App\Models\EnemyActionTable', 'fix_id', 'act_table'.$id)->first();
    }
    public function ability($id){
        return $this->hasOne('App\Models\EnemyAbility', 'fix_id', 'ability'.$id)->first();
    }
}
