<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Evo
 */
class Evo extends Model
{
    protected $table = 'evo';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'unit_id_pre',
        'unit_id_after',
        'unit_id_parts1',
        'unit_id_parts2',
        'unit_id_parts3',
        'unit_id_parts4',
        'friend_elem',
        'friend_kind',
        'friend_level',
        'money',
        'quest_id'
    ];

    protected $guarded = [];

    public function part($id){ return $this->hasOne('App\Models\Unit', 'fix_id', 'unit_id_parts'.$id)->first(); }
    public function partPre(){ return $this->hasOne('App\Models\Unit', 'fix_id', 'unit_id_pre')->first(); }
    public function partAfter(){ return $this->hasOne('App\Models\Unit', 'fix_id', 'unit_id_after')->first(); }
    public function quest(){ return $this->hasOne('App\Models\Quest', 'fix_id', 'quest_id')->first(); }
}
