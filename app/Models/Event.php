<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 */
class Event extends Model
{
    protected $table = 'event';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'period_type',
        'cycle_date_type',
        'cycle_timing_start',
        'cycle_active_hour',
        'timing_start',
        'timing_end',
        'user_group',
        'event_id',
        'event_schedule_show'
    ];

    protected $guarded = [];

    public function area(){ return $this->hasOne('App\Models\Area', 'event_id', 'event_id')->first(); }
}
