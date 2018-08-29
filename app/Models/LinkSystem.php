<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LinkSystem
 */
class LinkSystem extends Model
{
    protected $table = 'link_system';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'elem',
        'kind',
        'rare',
        'hp',
        'atk',
        'crt',
        'bst'
    ];

    protected $guarded = [];


}
