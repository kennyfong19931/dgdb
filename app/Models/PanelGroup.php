<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PanelGroup
 */
class PanelGroup extends Model
{
    protected $table = 'panel_group';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'panel_id_1',
        'panel_id_2',
        'panel_id_3',
        'panel_id_4',
        'panel_id_5',
        'panel_id_6',
        'panel_id_7'
    ];

    protected $guarded = [];

    public function panel($id){
        return $this->hasOne('App\Models\Panel', 'fix_id', 'panel_id_'.$id)->first();
    }
}
