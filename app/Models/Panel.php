<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Panel
 */
class Panel extends Model
{
    protected $table = 'panel';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'name',
        'res_panel',
        'detail',
        'effective_type',
        'effective_value',
        'trap_type'
    ];

    protected $guarded = [];


}
