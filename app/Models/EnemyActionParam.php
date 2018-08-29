<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Util\FunctionUtil;

/**
 * Class EnemyActionParam
 */
class EnemyActionParam extends Model
{
    protected $table = 'enemy_action_param';

    protected $primaryKey = 'fix_id';

	public $timestamps = true;

    protected $fillable = [
        'fix_id',
        'skill_name',
        'add_fix_id',
        'se_id',
        'skill_type',
        'skill_param1',
        'skill_param2',
        'skill_param3',
        'skill_param4',
        'skill_param5',
        'skill_param6',
        'skill_param7',
        'skill_param8',
        'skill_param9',
        'skill_param10',
        'skill_param11',
        'skill_param12',
        'skill_param13',
        'skill_param14',
        'skill_param15',
        'skill_param16',
        'attack_motion',
        'damage_effect',
        'damage_draw',
        'status_ailment_target',
        'status_ailment1',
        'status_ailment2',
        'status_ailment3',
        'status_ailment4',
        'audio_data_id'
    ];

    protected $guarded = [];

    private function getElement($element){
        switch ($element){
			case 0:
				$text="";
				break;
			case 1:
				$text='隨機';
				break;
			case 2:
				$text='炎';
				break;
			case 3:
				$text='水';
				break;
			case 4:
				$text='風';
				break;
			case 5:
				$text='光';
				break;
			case 6:
				$text='暗';
				break;
			case 7:
				$text='無';
				break;
			case 8:
				$text='心';
				break;
		}
		return $text;
    }

    public function getDetail($atk){
        $function = new FunctionUtil();
        $return['name'] = $this->skill_name;
        switch ($this->skill_type){
            case 1:
                $return['detail'] = '發呆';
                break;
            case 2:
                $return['detail'] = '攻擊 '.floor($atk*($this->skill_param1/100)).' ('.$this->skill_param1.'%)';
                break;
            case 3:
                $return['detail'] = $function->getElement($this->skill_param1).'重擊'.floor($atk*($this->skill_param2/100)).' ('.$this->skill_param2.'%)';
                break;
            case 5:
                $text = '轉色攻擊 ('.floor($atk).') ';
                /*$numArr = array();
                $elemArr = array();
                $numArr[1] = $this->skill_param7;
                $numArr[2] = $this->skill_param2;
                $numArr[3] = $this->skill_param3;
                $numArr[4] = $this->skill_param5;
                $numArr[5] = $this->skill_param6;
                $numArr[6] = $this->skill_param4;
                $numArr[7] = $this->skill_param8;
                for($i = 1; $i<8; $i++){
                    if($this->skill_param1 == 2){
                        if($numArr[$i] != 0){
                            $elemArr[$i] = $numArr[$i];
                        } else {
                            $elemArr[$i] = 8;
                        }
                    } else {
                        $elemArr[$i] = $numArr[$i];
                    }
                }
                $elemArr2 = $elemArr;
                $elemArr = array_unique($elemArr);
                $elemArr = array_values($elemArr);
                if(sizeof($elemArr) == 1){
                    if($elemArr[0] != 8)
                        $text .= '全部 → '.$this->getElement($elemArr[0]);
                    else
                        $text .= '手牌隨機變換';
                } else {
                    for($i = 1; $i<8; $i++){
                        //$text .= ' '.$this->getElement($elemArr2[$i]).' → '.$this->getElement($numArr[$i]);
                        $text .= ' '.$elemArr2[$i].' → '.$numArr[$i];
                    }
                }*/
                $arr = array($this->skill_param2, $this->skill_param3, $this->skill_param4, $this->skill_param5, $this->skill_param6, $this->skill_param7, $this->skill_param8);
                $arr2 = $arr;
                $arr = array_unique($arr);
                $arr = array_values($arr);

                if($this->skill_param1 != 0)
                    $text .= '手牌隨機變換';
                else if(sizeof($arr) == 1)
                    $text .= '全部 → '.$this->getElement($arr[0]);
                else{
                    $count = 2;
                    foreach($arr2 as $val){
                        if($val != 0 && $val != 2)
                            $text .= $this->getElement($count).' → '.$function->getElement($val);
                        $count++;
                    }
                }
                $return['detail'] = $text;
                break;
            case 6:
                if($this->skill_param1 == 2)
                    $return['detail'] = '重擊 - 隊伍最大HP的'.$this->skill_param2.'%';
                else
                    $return['detail'] = '重擊 - 隊伍現時HP的'.$this->skill_param2.'%';
                break;
            case 7:
                switch($this->skill_param1){
                    case 1:
                        $text = '敵人';
                        break;
                    case 2:
                    case 4:
                        $text = '我方';
                        break;
                    case 3:
                        $text = '全體敵人';
                        break;
                    case 5:
                        $text = '我方及全體敵人';
                        break;
                }
                $text .= '回復';
                if($this->skill_param2 == 2)
                    $text .= $this->skill_param4.'%';
                else
                    $text .= $this->skill_param4.'點';
                $text .= '血量';
                $return['detail'] = $text;
                break;
            case 8:
                $return['detail'] = '消除全體敵人所有效果及狀態異常';
                break;
            case 9:
                $return['detail'] = '消除我方所有效果及異常狀態';
                break;
            case 10:
                $return['detail'] = '放板格內容更變';
                /* SkillBattlefieldPanel(int nCategory, int[] anParamList, ESKILLTYPE eSkillType)
                $num2 = $this->skill_param2;
                $num4 = $this->skill_param14;
                $numArr[0] = $this->skill_param4;   // element
                $numArr2[0] = $this->skill_param5;  // count
                $numArr[1] = $this->skill_param6;
                $numArr2[1] = $this->skill_param7;
                $numArr[2] = $this->skill_param8;
                $numArr2[2] = $this->skill_param9;
                $numArr[3] = $this->skill_param10;
                $numArr2[3] = $this->skill_param11;
                $numArr[4] = $this->skill_param12;
                $numArr2[4] = $this->skill_param13;*/
                break;
            case 11:
                $return['detail'] = '';
                break;
        }
        if($this->status_ailment1 != 0){
            $return['status_ailment'][] = $function->statusAilment($this->getStatusAilment(1),$this->status_ailment_target);
        }
        if($this->status_ailment2 != 0){
            $return['status_ailment'][] = $function->statusAilment($this->getStatusAilment(2),$this->status_ailment_target);
        }
        if($this->status_ailment3 != 0){
            $return['status_ailment'][] = $function->statusAilment($this->getStatusAilment(3),$this->status_ailment_target);
        }
        if($this->status_ailment4 != 0){
            $return['status_ailment'][] = $function->statusAilment($this->getStatusAilment(4),$this->status_ailment_target);
        }
        return $return;
    }

    public function getAbilityDetail(){
        $function = new FunctionUtil();
        switch ($this->skill_type){
            case 1:
                $return['detail'] = '發呆';
                break;
            case 2:
                $return['detail'] = '攻擊';
                break;
            case 3:
                $return['detail'] = $function->getElement($this->skill_param1).'重擊';
                break;
            case 5:
                $text = '轉色攻擊 ('.floor($atk).') ';
                $arr = array($this->skill_param2, $this->skill_param3, $this->skill_param4, $this->skill_param5, $this->skill_param6, $this->skill_param7, $this->skill_param8);
                $arr2 = $arr;
                $arr = array_unique($arr);
                $arr = array_values($arr);

                if($this->skill_param1 != 0)
                    $text .= '手牌隨機變換';
                else if(sizeof($arr) == 1)
                    $text .= '全部 → '.$this->getElement($arr[0]);
                else{
                    $count = 2;
                    foreach($arr2 as $val){
                        if($val != 0 && $val != 2)
                            $text .= $this->getElement($count).' → '.$function->getElement($val);
                        $count++;
                    }
                }
                $return['detail'] = $text;
                break;
            case 6:
                if($this->skill_param1 == 2)
                    $return['detail'] = '重擊 - 隊伍最大HP的'.$this->skill_param2.'%';
                else
                    $return['detail'] = '重擊 - 隊伍現時HP的'.$this->skill_param2.'%';
                break;
            case 7:
                switch($this->skill_param1){
                    case 1:
                        $text = '敵人';
                        break;
                    case 2:
                    case 4:
                        $text = '我方';
                        break;
                    case 3:
                        $text = '全體敵人';
                        break;
                    case 5:
                        $text = '我方及全體敵人';
                        break;
                }
                $text .= '回復';
                if($this->skill_param2 == 2)
                    $text .= $this->skill_param4.'%';
                else
                    $text .= $this->skill_param4.'點';
                $text .= '血量';
                $return['detail'] = $text;
                break;
            case 8:
                $return['detail'] = '消除全體敵人所有效果及狀態異常';
                break;
            case 9:
                $return['detail'] = '消除我方所有效果及異常狀態';
                break;
            case 10:
                $return['detail'] = '放板格內容更變';
                break;
            case 11:
                $return['detail'] = '';
                break;
        }
        if($this->status_ailment1 != 0){
            $return['status_ailment'][] = $function->statusAilment($this->getStatusAilment(1),$this->status_ailment_target);
        }
        if($this->status_ailment2 != 0){
            $return['status_ailment'][] = $function->statusAilment($this->getStatusAilment(2),$this->status_ailment_target);
        }
        if($this->status_ailment3 != 0){
            $return['status_ailment'][] = $function->statusAilment($this->getStatusAilment(3),$this->status_ailment_target);
        }
        if($this->status_ailment4 != 0){
            $return['status_ailment'][] = $function->statusAilment($this->getStatusAilment(4),$this->status_ailment_target);
        }
        return $return;
    }
    public function getStatusAilment($num){ return $this->hasOne('App\Models\StatusAilment', 'fix_id', 'status_ailment'.$num)->first(); }
}
