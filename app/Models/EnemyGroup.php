<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EnemyGroup
 */
class EnemyGroup extends Model
{
    protected $table = 'enemy_group';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'fix',
        'num_min',
        'num_max',
        'enemy_id_1',
        'enemy_id_2',
        'enemy_id_3',
        'enemy_id_4',
        'enemy_id_5',
        'enemy_id_6',
        'enemy_id_7',
        'chain_id',
        'chain_turn_offset',
        'drop_type'
    ];

    protected $guarded = [];

    public function enemy($id){ return $this->hasOne('App\Models\Enemy', 'fix_id', 'enemy_id_'.$id)->first(); }
    public function chain(){
        return $this->hasOne('App\Models\EnemyGroup', 'fix_id', 'chain_id')->first();
    }
}
