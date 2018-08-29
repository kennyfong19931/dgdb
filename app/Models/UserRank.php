<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserRank
 */
class UserRank extends Model
{
    protected $table = 'user_rank';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'exp_next',
        'exp_next_total',
        'stamina',
        'friend_max',
        'unit_max',
        'party_cost'
    ];

    protected $guarded = [];


}
