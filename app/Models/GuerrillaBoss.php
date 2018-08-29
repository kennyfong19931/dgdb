<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GuerrillaBoss
 */
class GuerrillaBoss extends Model
{
    protected $table = 'guerrilla_boss';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'timing_start',
        'timing_end',
        'user_group',
        'enemy_group_id',
        'enemy_rate',
        'quest_id',
        'quest_id_must'
    ];

    protected $guarded = [];

    public function enemyGroup(){ return $this->hasOne('App\Models\EnemyGroup', 'fix_id', 'enemy_group_id')->first(); }
    public function quest(){ return $this->hasOne('App\Models\Quest', 'fix_id', 'quest_id')->first(); }
}
