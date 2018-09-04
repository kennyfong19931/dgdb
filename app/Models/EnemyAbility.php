<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Util\FunctionUtil;

/**
 * Class EnemyAbility
 */
class EnemyAbility extends Model
{
    protected $table = 'enemy_ability';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'name',
        'detail',
        'detailcn',
        'icon',
        'category',
        'param_00',
        'param_01',
        'param_02',
        'param_03',
        'param_04',
        'param_05',
        'param_06',
        'param_07',
        'param_08',
        'param_09',
        'param_10',
        'param_11',
        'param_12',
        'param_13',
        'param_14',
        'param_15'
    ];

    protected $guarded = [];

    public function getDetailCn(){
        $function = new FunctionUtil();
        $text = '[ffff00]■';
        switch($this->category){
            case 1:
                if($this->param_00 == 1)
                    $text .= '敵方';
                else if($this->param_00 == 2)
                    $text .= '我方';
                switch($this->param_01){
                    case 1:
                        $text .= '【毒】狀態時,';
                        break;
                    case 3:
                        $text .= '【防禦力下降】狀態時,';
                        break;
                    case 4:
                        $text .= '受到延遲・所受傷害增加時,';
                        break;
                    case 8:
                        $text .= '【攻擊力下降】狀態時,';
                        break;
                    case 11:
                        $text .= '【封印】狀態時,';
                        break;
                }
                if($this->param_05 == 1)
                    $text .= '我方';
                else if($this->param_05 == 2)
                    $text .= '敵方';
                if($this->param_06 < 100)
                    $text .= '攻擊力-'.(100-$this->param_06).'%';
                else
                    $text .= '攻擊力+'.($this->param_06-100).'%';
                break;
            case 2:
                $text .= 'Critical外的NS攻擊傷害-'.(100-$this->param_01).'%';
                break;
            case 3:
                if($this->param_00 == 1)
                    $text .= '單體';
                else if($this->param_00 == 2)
                    $text .= '全體';
                $text .= 'NS攻擊力-'.(100-$this->param_01).'%';
                break;
            case 4:
                if(!($this->param_03 == $this->param_04 && $this->param_04 == $this->param_05 && $this->param_05 == $this->param_06)){
                    //param_02 skill_cate
                    if($this->param_03 == 2)
                        $text .= '造成傷害以外的';
                    if($this->param_04 == 2)
                        $text .= '固定傷害以外的';
                    if($this->param_05 == 2)
                        $text .= '造成血量百分比傷害以外的';
                    if($this->param_06 == 2)
                        $text .= '回復以外的';
                    //param_07 skill_ailment_category
                }
                if($this->param_01 == 1)
                    $text .= '單體';
                else if($this->param_01 == 2)
                    $text .= '全體';
                $text .= 'AS攻擊傷害-'.(100-$this->param_08).'%';
                break;
            case 5:
                $text .= 'Boost Skill 攻擊傷害-'.(100-$this->param_00).'%';
                break;
            case 6:
                if($this->param_00 == 2)
                    $text .= '1板';
                else if($this->param_00 > 2)
                    $text .= ($this->param_00-1).'板或以下';
                $text .= 'NS攻擊傷害'.($this->param_01 - $this->param_02).'%';
                //param_01 以上
                break;
            case 7:
                if($this->param_02 != 100)
                    $text .= ($this->param_00).'屬性或以下, NS攻擊力-'.(100-$this->param_02).'%';
                if($this->param_01 != 100)
                    $text .= ($this->param_00).'屬性或以上, NS攻擊力+'.($this->param_01-100).'%';
                break;
            case 8:
                if($this->param_02 != 100)
                    $text .= $this->param_00.' combo以下, NS攻擊力-'.(100-$this->param_02).'%';
                if($this->param_01 != 100)
                    $text .= $this->param_00.' combo以上, NS攻擊力-'.(100-$this->param_01).'%';
                break;
            case 9:
                if($this->param_02 != 100)
                    $text .= 'Rate'.(($this->param_00-1)/100).'以下, NS攻擊力-'.(100-$this->param_02).'%';
                if($this->param_01 != 100)
                    $text .= 'Rate'.(($this->param_00-1)/100).'以上, NS攻擊力-'.(100-$this->param_01).'%';
                break;
            case 10:
                //param_00 skill_elem
                if($this->param_00 != 0){
                    $text .= '發動'.$function->getElement($this->param_00).'屬性AS時';
                }
                //param_01 skill_type
                if($this->param_01 != 0){
                    switch($this->param_01){
                        case 1:
                            $text .= '發動單體攻擊AS時';
                            break;
                        case 2:
                            $text .= '發動全體攻擊AS時';
                            break;
                        case 5:
                            $text .= '發動血量回復AS時';
                            break;
                        case 6:
                            $text .= '發動SP回覆AS時';
                            break;
                        case 7:
                            $text .= '發動攻擊時間增加AS時';
                            break;
                    }
                }
                // TODO param_02 skill_cate
                if($this->param_02 != 0){
                    switch($this->param_02){
                        case 28:
                            $text .= '發動血量回復AS時';
                            break;
                        case 29:
                            $text .= '發動血量%回復AS時';
                            break;
                        case 44:
                            $text .= '發動CD數減AS時';
                            break;
                        case 45:
                            $text .= '發動直接傳送到終點門前AS時';
                            break;
                        case 46:
                            $text .= '發動打開所有？格AS時';
                            break;
                        case 47:
                            $text .= '發動各種效果、異常狀態消失AS時';
                            break;
                    }
                }
                if($this->param_03 == 2)
                    $text .= '受到AS攻擊傷害傷害時';
                if($this->param_04 == 2)
                    $text .= '受到AS固定傷害時';
                if($this->param_05 == 2)
                    $text .= '受到AS血量百分比傷害時';
                if($this->param_06 == 2)
                    $text .= 'AS回復時';
                //param_07 skill_ailment_category
                $enemyActionParam = EnemyActionParam::where('fix_id','=',$this->param_08)->first();
                if($enemyActionParam != null){
                    $action = $enemyActionParam->getAbilityDetail();
                    $text .= $action['detail'];
                    if(isset($action['status_ailment']))
                        foreach($action['status_ailment'] as $status_ailment){
                            $text .= '('.$status_ailment.')';
                        }
                } else {
                    $text .= '反擊';
                }
                break;
            case 11:
                $text .= '一撃必殺無效';
                break;
            default:
                $text .= $this->detailcn;
                break;
        }
        $text .= '[-]';
        return $text;
    }
}
