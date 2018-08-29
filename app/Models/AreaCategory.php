<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AreaCategory
 */
class AreaCategory extends Model
{
    protected $table = 'area_category';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'area_cate_name',
        'area_cate_type',
        'questlist_sort',
        'area_cate_detail'
    ];

    protected $guarded = [];

    public function getAreas(){ return $this->hasMany('App\Models\Area', 'area_cate_id', 'fix_id')->get(); }
}
