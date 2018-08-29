<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Quest;
use App\Models\Unit;

/**
 * Class Translate
 */
class Translate extends Model
{
    protected $table = 'translate';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'type',
        'id',
        'text',
        'ip'
    ];

    protected $guarded = [];
}
