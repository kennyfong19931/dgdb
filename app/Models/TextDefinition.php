<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TextDefinition
 */
class TextDefinition extends Model
{
    protected $table = 'text_definition';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'text_key',
        'text'
    ];

    protected $guarded = [];


}
