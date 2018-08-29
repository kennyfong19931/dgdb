<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AudioDatum
 */
class AudioData extends Model
{
    protected $table = 'audio_data';

    public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'group_id',
        'ducking_disable',
        'res_name',
        'vol_lv',
        'rand_id_00',
        'rand_id_01',
        'rand_id_02',
        'rand_id_03',
        'rand_id_04',
        'rand_id_05'
    ];

    protected $guarded = [];


}
