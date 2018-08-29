<?php
namespace App\Util;
use App\Models\LinkSystem;
use App\Models\Enemy;
use App\Models\EnemyGroup;
use App\Models\Quest;
use App\Models\QuestFloor;
use Carbon\Carbon;

class FunctionUtil{
	private $site = 'http://img.qov.tw/';

	public function getElement($element){
		switch ($element){
			case 0:
				return "所有";
				break;
			case 1:
				return '無';
				break;
			case 2:
				return '炎';
				break;
			case 3:
				return '水';
				break;
			case 4:
				return '光';
				break;
			case 5:
				return '暗';
				break;
			case 6:
				return '風';
				break;
			case 7:
				return '心';
				break;
		}
	}

	public function getKind($kind){
		$text;
		switch ($kind){
			case 0:
				$text="無指定";
				break;
			case 1:
				$text="人";
				break;
			case 2:
				$text="龍";
				break;
			case 3:
				$text="神";
				break;
			case 4:
				$text="魔物";
				break;
			case 5:
				$text="妖精";
				break;
			case 6:
				$text="獸";
				break;
			case 7:
				$text="機械";
				break;
			case 8:
				$text="強化合成";
				break;
		}
		return $text;
	}

	public function getRequirement($id){
		switch($id){
            case 1:
                return array($this->getElement(2), null);
            case 2:
                return array($this->getElement(3), null);
            case 3:
                return array($this->getElement(6), null);
            case 4:
                return array($this->getElement(4), null);
            case 5:
                return array($this->getElement(5), null);
            case 6:
                return array($this->getElement(1), null);
            case 7:
                return array($this->getElement(7), null);
            case 8:
                //return array($this->getElement(7).'以外', 1);
				return array(null, 1);
            case 9:
                //return array($this->getElement(7).'以外', 2);
				return array(null, 2);
            case 10:
                //return array($this->getElement(7).'以外', 3);
				return array(null, 3);
            case 11:
                //return array($this->getElement(7).'以外', 4);
				return array(null, 4);
            case 12:
                //return array($this->getElement(7).'以外', 5);
				return array(null, 5);
            case 13:
                return array($this->getElement(2), 1);
            case 14:
				return array($this->getElement(2), 2);
            case 15:
				return array($this->getElement(2), 3);
            case 16:
				return array($this->getElement(2), 4);
            case 17:
				return array($this->getElement(2), 5);
            case 18:
				return array($this->getElement(3), 1);
            case 19:
				return array($this->getElement(3), 2);
            case 20:
				return array($this->getElement(3), 3);
            case 21:
				return array($this->getElement(3), 4);
            case 22:
				return array($this->getElement(3), 5);
            case 23:
				return array($this->getElement(6), 1);
            case 24:
				return array($this->getElement(6), 2);
            case 25:
				return array($this->getElement(6), 3);
            case 26:
				return array($this->getElement(6), 4);
            case 27:
				return array($this->getElement(6), 5);
            case 28:
				return array($this->getElement(4), 1);
            case 29:
				return array($this->getElement(4), 2);
            case 30:
				return array($this->getElement(4), 3);
            case 31:
				return array($this->getElement(4), 4);
            case 32:
				return array($this->getElement(4), 5);
            case 33:
				return array($this->getElement(5), 1);
            case 34:
				return array($this->getElement(5), 2);
            case 35:
				return array($this->getElement(5), 3);
            case 36:
				return array($this->getElement(5), 4);
            case 37:
				return array($this->getElement(5), 5);
            case 38:
				return array($this->getElement(1), 1);
            case 39:
				return array($this->getElement(1), 2);
            case 40:
				return array($this->getElement(1), 3);
            case 41:
				return array($this->getElement(1), 4);
            case 42:
				return array($this->getElement(1), 5);
            case 43:
				return array($this->getElement(7), 1);
            case 44:
				return array($this->getElement(7), 2);
            case 45:
				return array($this->getElement(7), 3);
            case 46:
				return array($this->getElement(7), 4);
            case 47:
				return array($this->getElement(7), 5);
			default:
				return null;
		}
	}

    public function getTriId($id){
		if($id<10){
			return '00'.$id;
		} else if ($id<100){
			return '0'.$id;
		} else {
			return $id;
		}
    }

	public function statusAilment($obj, $target = 0, $statusPow = null){
		if($target == 1)
			$target = '敵人';
		else if($target == 2)
			$target = '我方';
		else if($target == 4)
			$target = '所有敵人';
		else
			$target = '';
		switch ($obj->category){
			case 1:
				return '<img src="'.$this->site.'icon/icon_damage_down.png" alt="icon_damage_down"/>'.$target.$obj->duration.'回合減低受到的傷害'.(100-$obj->param01).'%';
				break;
			case 2:
				return '<img src="'.$this->site.'icon/icon_damage_up.png" alt="icon_damage_up"/>'.$target.$obj->duration.'回合防禦力下降'.(100-$obj->param01).'%';
				break;
			case 3:
				if($statusPow == null)
					return '<img src="'.$this->site.'icon/icon_trap_poison.png" alt="icon_trap_poison"/>'.$target.$obj->duration.'回合中毒 ('.($obj->param01/100).'倍攻擊力)';
				else
					return '<img src="'.$this->site.'icon/icon_trap_poison.png" alt="icon_trap_poison"/>'.$target.$obj->duration.'回合中毒 (每回合造成'.floor($statusPow*($obj->param01/100)).'固定傷害('.($obj->param01/100).'倍攻擊力))';
				break;
			// 4: panic
			// 5: icon_heal_happytreasure
			// 6: icon_heal_moneyup (battle_money_up)
			// 7: fear
			case 8:
				return '<img src="'.$this->site.'icon/icon_time_extend.png" alt="icon_time_extend"/>'.$obj->duration.'回合內、每一turn的攻擊時間增加'.$obj->param01.'秒 (最多加至9秒)';
				break;
			// 9: icon_heal_torch
			// 10: icon_trap_dark
			// 11: alarm
			case 12:
				return '<img src="'.$this->site.'icon/icon_atk_up_all.png" alt="icon_atk_up_all"/>'.$target.$obj->duration.'回合攻擊力增強至'.$obj->param01.'%';
				break;
			case 13:
				return '<img src="'.$this->site.'icon/icon_atk_up_fire.png" alt="icon_atk_up_fire"/>'.$target.$obj->duration.'回合炎屬性攻擊力增強至'.$obj->param01.'%';
				break;
			case 14:
				return '<img src="'.$this->site.'icon/icon_atk_up_water.png" alt="icon_atk_up_water"/>'.$target.$obj->duration.'回合水屬性攻擊力增強至'.$obj->param01.'%';
				break;
			case 15:
				return '<img src="'.$this->site.'icon/icon_atk_up_light.png" alt="icon_atk_up_light"/>'.$target.$obj->duration.'回合光屬性攻擊力增強至'.$obj->param01.'%';
				break;
			case 16:
				return '<img src="'.$this->site.'icon/icon_atk_up_dark.png" alt="icon_atk_up_dark"/>'.$target.$obj->duration.'回合暗屬性攻擊力增強至'.$obj->param01.'%';
				break;
			case 17:
				return '<img src="'.$this->site.'icon/icon_atk_up_wind.png" alt="icon_atk_up_wind"/>'.$target.$obj->duration.'回合風屬性攻擊力增強至'.$obj->param01.'%';
				break;
			case 18:
				return $target.$obj->duration.'回合心板攻擊力增強至'.$obj->param01.'%';
				break;
			case 19:
				return '<img src="'.$this->site.'icon/icon_atk_up_naught.png" alt="icon_atk_up_naught"/>'.$target.$obj->duration.'回合無屬性攻擊力增強至'.$obj->param01.'%';
				break;
			case 20:
				return '<img src="'.$this->site.'icon/icon_panel_fire.png" alt="icon_panel_fire"/>'.$target.$obj->duration.'回合內手上的牌變成炎板';
				break;
			case 21:
				return '<img src="'.$this->site.'icon/icon_panel_water.png" alt="icon_panel_water"/>'.$target.$obj->duration.'回合內手上的牌變成水板';
				break;
			case 22:
				return '<img src="'.$this->site.'icon/icon_panel_light.png" alt="icon_panel_light"/>'.$target.$obj->duration.'回合內手上的牌變成光板';
				break;
			case 23:
				return '<img src="'.$this->site.'icon/icon_panel_dark.png" alt="icon_panel_dark"/>'.$target.$obj->duration.'回合內手上的牌變成暗板';
				break;
			case 24:
				return '<img src="'.$this->site.'icon/icon_panel_wind.png" alt="icon_panel_wind"/>'.$target.$obj->duration.'回合內手上的牌變成風板';
				break;
			case 25:
				return '<img src="'.$this->site.'icon/icon_panel_heart.png" alt="icon_panel_heart"/>'.$target.$obj->duration.'回合內手上的牌變成心板';
				break;
			case 26:
				return '<img src="'.$this->site.'icon/icon_panel_naught.png" alt="icon_panel_naught"/>'.$target.$obj->duration.'回合內手上的牌變成無板';
				break;
			case 27:
				return '<img src="'.$this->site.'icon/icon_damage_down_fire.png" alt="icon_damage_down_fire"/>'.$target.$obj->duration.'回合所受火屬性的傷害下降至'.$obj->param01.'%';
				break;
			case 28:
				return '<img src="'.$this->site.'icon/icon_damage_down_water.png" alt="icon_damage_down_water"/>'.$target.$obj->duration.'回合所受水屬性的傷害下降至'.$obj->param01.'%';
				break;
			case 29:
				return '<img src="'.$this->site.'icon/icon_damage_down_light.png" alt="icon_damage_down_light"/>'.$target.$obj->duration.'回合所受光屬性的傷害下降至'.$obj->param01.'%';
				break;
			case 30:
				return '<img src="'.$this->site.'icon/icon_damage_down_dark.png" alt="icon_damage_down_dark"/>'.$target.$obj->duration.'回合所受暗屬性的傷害下降至'.$obj->param01.'%';
				break;
			case 31:
				return '<img src="'.$this->site.'icon/icon_damage_down_wind.png" alt="icon_damage_down_wind"/>'.$target.$obj->duration.'回合所受風屬性的傷害下降至'.$obj->param01.'%';
				break;
			case 32:
				return $target.$obj->duration.'回合所受心板的傷害下降至'.$obj->param01.'%';
				break;
			case 33:
				return '<img src="'.$this->site.'icon/icon_damage_down_naught.png" alt="icon_damage_down_naught"/>'.$target.$obj->duration.'回合所受無屬性的傷害下降至'.$obj->param01.'%';
				break;
			case 34:
				return '<img src="'.$this->site.'icon/icon_atk_up_human.png" alt="icon_atk_up_human"/>'.$target.$obj->duration.'回合人類寵物攻擊力上升至'.$obj->param01.'%';
				break;
			case 35:
				return '<img src="'.$this->site.'icon/icon_atk_up_dragon.png" alt="icon_atk_up_dragon"/>'.$target.$obj->duration.'回合龍類寵物攻擊力上升至'.$obj->param01.'%';
				break;
			case 36:
				return '<img src="'.$this->site.'icon/icon_atk_up_god.png" alt="icon_atk_up_god"/>'.$target.$obj->duration.'回合神類寵物攻擊力上升至'.$obj->param01.'%';
				break;
			case 37:
				return '<img src="'.$this->site.'icon/icon_atk_up_demon.png" alt="icon_atk_up_demon"/>'.$target.$obj->duration.'回合魔物類寵物攻擊力上升至'.$obj->param01.'%';
				break;
			case 38:
				return '<img src="'.$this->site.'icon/icon_atk_up_fairy.png" alt="icon_atk_up_fairy"/>'.$target.$obj->duration.'回合妖精類寵物攻擊力上升至'.$obj->param01.'%';
				break;
			case 39:
				return '<img src="'.$this->site.'icon/icon_atk_up_beast.png" alt="icon_atk_up_beast"/>'.$target.$obj->duration.'回合獸類寵物攻擊力上升至'.$obj->param01.'%';
				break;
			case 40:
				return '<img src="'.$this->site.'icon/icon_atk_up_machine.png" alt="icon_atk_up_machine"/>'.$target.$obj->duration.'回合機械類寵物攻擊力上升至'.$obj->param01.'%';
				break;
			// 41: n/a
			case 42:
				return '<img src="'.$this->site.'icon/icon_block_as.png" alt="icon_block_as"/>'.$target.$obj->duration.'回合禁止使用主動技能';
				break;
			// 43: n/a
			case 44:
				return '<img src="'.$this->site.'icon/icon_atk_down_all.png" alt="icon_atk_down_all"/>'.$target.$obj->duration.'回合攻擊力減弱至'.$obj->param01.'%';
				break;
			case 45:
				return '<img src="'.$this->site.'icon/icon_atk_down_fire.png" alt="icon_atk_down_fire"/>'.$target.$obj->duration.'回合炎屬性攻擊力減弱至'.$obj->param01.'%';
				break;
			case 46:
				return '<img src="'.$this->site.'icon/icon_atk_down_water.png" alt="icon_atk_down_water"/>'.$target.$obj->duration.'回合水屬性攻擊力減弱至'.$obj->param01.'%';
				break;
			case 47:
				return '<img src="'.$this->site.'icon/icon_atk_down_light.png" alt="icon_atk_down_light"/>'.$target.$obj->duration.'回合光屬性攻擊力減弱至'.$obj->param01.'%';
				break;
			case 48:
				return '<img src="'.$this->site.'icon/icon_atk_down_dark.png" alt="icon_atk_down_dark"/>'.$target.$obj->duration.'回合暗屬性攻擊力減弱至'.$obj->param01.'%';
				break;
			case 49:
				return '<img src="'.$this->site.'icon/icon_atk_down_wind.png" alt="icon_atk_down_wind"/>'.$target.$obj->duration.'回合風屬性攻擊力減弱至'.$obj->param01.'%';
				break;
			case 50:
				return ''.$target.$obj->duration.'回合心板攻擊力減弱至'.$obj->param01.'%';
				break;
			case 51:
				return '<img src="'.$this->site.'icon/icon_atk_down_naught.png" alt="icon_atk_down_naught"/>'.$target.$obj->duration.'回合無屬攻擊力減弱至'.$obj->param01.'%';
				break;
			case 52:
				return '<img src="'.$this->site.'icon/icon_damage_up_all.png" alt="icon_damage_up_all"/>'.$target.$obj->duration.'回合受到的傷害上升至'.$obj->param01.'%';
				break;
			case 53:
				return '<img src="'.$this->site.'icon/icon_damage_up_fire.png" alt="icon_damage_up_fire"/>'.$target.$obj->duration.'回合受到的炎屬性傷害上升至'.$obj->param01.'%';
				break;
			case 54:
				return '<img src="'.$this->site.'icon/icon_damage_up_water.png" alt="icon_damage_up_water"/>'.$target.$obj->duration.'回合受到的水屬性傷害上升至'.$obj->param01.'%';
				break;
			case 55:
				return '<img src="'.$this->site.'icon/icon_damage_up_light.png" alt="icon_damage_up_light"/>'.$target.$obj->duration.'回合受到的光屬性傷害上升至'.$obj->param01.'%';
				break;
			case 56:
				return '<img src="'.$this->site.'icon/icon_damage_up_dark.png" alt="icon_damage_up_dark"/>'.$target.$obj->duration.'回合受到的暗屬性傷害上升至'.$obj->param01.'%';
				break;
			case 57:
				return '<img src="'.$this->site.'icon/icon_damage_up_wind.png" alt="icon_damage_up_wind"/>'.$target.$obj->duration.'回合受到的風屬性傷害上升至'.$obj->param01.'%';
				break;
			case 58:
				return $target.$obj->duration.'回合受到的心板傷害上升至'.$obj->param01.'%';
				break;
			case 59:
				return '<img src="'.$this->site.'icon/icon_damage_up_naught.png" alt="icon_damage_up_naught"/>'.$target.$obj->duration.'回合受到的無屬性傷害上升至'.$obj->param01.'%';
				break;
			case 60:
				return '<img src="'.$this->site.'icon/icon_atk_down_god.png" alt="icon_atk_down_god"/>'.$target.$obj->duration.'回合神類寵物攻擊力減弱至'.$obj->param01.'%';
				break;
			case 61:
				return '<img src="'.$this->site.'icon/icon_atk_down_beast.png" alt="icon_atk_down_beast"/>'.$target.$obj->duration.'回合獸類寵物攻擊力減弱至'.$obj->param01.'%';
				break;
			case 62:
				return '<img src="'.$this->site.'icon/icon_atk_down_demon.png" alt="icon_atk_down_demon"/>'.$target.$obj->duration.'回合魔物類寵物攻擊力減弱至'.$obj->param01.'%';
				break;
			case 63:
				return '<img src="'.$this->site.'icon/icon_atk_down_dragon.png" alt="icon_atk_down_dragon"/>'.$target.$obj->duration.'回合龍類寵物攻擊力減弱至'.$obj->param01.'%';
				break;
			case 64:
				return '<img src="'.$this->site.'icon/icon_atk_down_fairy.png" alt="icon_atk_down_fairy"/>'.$target.$obj->duration.'回合妖精類寵物攻擊力減弱至'.$obj->param01.'%';
				break;
			case 65:
				return '<img src="'.$this->site.'icon/icon_atk_down_human.png" alt="icon_atk_down_human"/>'.$target.$obj->duration.'回合人類寵物攻擊力減弱至'.$obj->param01.'%';
				break;
			case 66:
				return '<img src="'.$this->site.'icon/icon_atk_down_machine.png" alt="icon_atk_down_machine"/>'.$target.$obj->duration.'回合機械類寵物攻擊力減弱至'.$obj->param01.'%';
				break;
			case 67:
				if($obj->name == 'パネル出現率操作スキル無効')
					return $target.$obj->duration.'回合改變板Panel出現率的技能無效化';
				else
					return $target.$obj->duration.'回合'.$obj->name;
		}
	}

	public function getSkillDetailCn($type, $obj){
		$first = true;
		$text = '';
		switch($type){
			case 'l':
		        if($obj->skill_powup_elem_active == 2){
					if($first){$first = false;} else {$text .= '、';}
		            switch ($obj->skill_powup_elem_status){
		                case 1:
		                    $text .= '我方'.$this->getElement($obj->skill_powup_elem_type).'屬性寵物攻擊力'.($obj->skill_powup_elem_rate/100).'倍';
		                    break;
		                case 2:
		                    $text .= '我方'.$this->getElement($obj->skill_powup_elem_type).'屬性寵物血量'.($obj->skill_powup_elem_rate/100).'倍';
		                    break;
		                case 3:
		                    $text .=  '我方'.$this->getElement($obj->skill_powup_elem_type).'屬性寵物血量及攻擊力'.($obj->skill_powup_elem_rate/100).'倍';
		                    break;
		            }
		        }
				if($obj->skill_powup_kind_active == 2){
					if($first){$first = false;} else {$text .= '、';}
		            switch ($obj->skill_powup_kind_status){
		                case 1:
		                    $text .= '我方'.$this->getKind($obj->skill_powup_kind_type).'類寵物攻擊力'.($obj->skill_powup_kind_rate/100).'倍';
		                    break;
		                case 2:
		                    $text .= '我方'.$this->getKind($obj->skill_powup_kind_type).'類寵物血量'.($obj->skill_powup_kind_rate/100).'倍';
		                    break;
		                case 3:
		                    $text .=  '我方'.$this->getKind($obj->skill_powup_kind_type).'類寵物血量及攻擊力'.($obj->skill_powup_kind_rate/100).'倍';
		                    break;
		            }
		        }
				if($obj->skill_follow_atk_active == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= '敵方全體攻擊力'.($obj->skill_follow_atk_rate/100).'倍'.$this->getElement($obj->skill_follow_atk_element).'屬性追打';
				}
				if($obj->skill_decline_dmg_active == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= $this->getElement($obj->skill_decline_dmg_element).'屬性敵人造成的傷害減少'.(100-$obj->skill_decline_dmg_rate).'%';
				}
				if($obj->skill_recovery_move_active == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= '移動時每走一步血量'.$obj->skill_recovery_move_rate.'%回復';
				}
				if($obj->skill_recovery_battle_active == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= '戰鬥時每回合血量'.$obj->skill_recovery_battle_rate.'%回復';
				}
				if($obj->skill_quick_time_active == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= '每一turn的攻擊時間增加'.$obj->skill_quick_time_second.'秒（最多加至9秒）';
				}
				if($obj->skill_recovery_support_active == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= '回復效率上昇'.$obj->skill_recovery_support_rate.'%';
				}
				if($obj->skill_recovery_atk_active == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= '每一次成功攻擊、回復'.$obj->skill_recovery_atk_rate.'%血量';
				}
				if($obj->skill_hpfull_powup_active == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= '血量全滿時、我方全體寵物攻擊力'.($obj->skill_hpfull_powup_rate/100).'倍';
				}
				if($obj->skill_hpdown_powup_active == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= '血量'.$obj->skill_hpdown_powup_border.'%以下時、我方全體寵物攻擊力'.($obj->skill_hpdown_powup_rate/100).'倍';
				}
				if($obj->skill_mekuri_powup_active == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= 'Combo數越高攻擊力越高';
				}
				if($obj->skill_funbari_active == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= '能夠在一次攻擊使你致死時存活';	//$obj->skill_funbari_border
				}
				if($obj->skill_hpfull_guard_active == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= '血量全滿時、所受傷害下降'.$obj->skill_hpfull_guard_rate.'%';
				}
				if($obj->skill_initiative_atk_active == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= $obj->detailcn;
				}
				if($obj->skill_transform_card_active == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= '地下城內、所有'.$this->getElement($obj->skill_transform_card_root).'卡變成'.$this->getElement($obj->skill_transform_card_dest).'卡';
				}
				if($obj->skill_damageup_color_active == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= $obj->skill_damageup_color_count.'種屬性或以上同時攻擊時、攻擊力'.($obj->skill_damageup_color_rate/100).'倍';
				}
				if($obj->skill_damageup_hands_active == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= $obj->skill_damageup_hands_count.'combo以上、攻擊力'.$obj->skill_damageup_hands_rate.'%';
				}
				if($obj->skill_type == 1){
					if($first){$first = false;} else {$text .= '、';}
					$first2 = true;
					$text .= '我方';
					if($obj->skill_value_01 != 0){
						if($first2){$first2 = false;} else {$text .= '或';}
						$text .= $this->getElement($obj->skill_value_01).'屬性';
					}
					if($obj->skill_value_06 != 0){
						if($first2){$first2 = false;} else {$text .= '或';}
						$text .= $this->getKind($obj->skill_value_06).'類';
					}
					if($obj->skill_value_07 != 0){
						if($first2){$first2 = false;} else {$text .= '或';}
						$text .= $this->getKind($obj->skill_value_07).'類';
					}
					$text .= '寵物';
					if($obj->skill_value_11 == $obj->skill_value_12)
						$text .= '血量及攻擊力'.($obj->skill_value_11/100).'倍';
					else {
						$first3 = true;
						if($obj->skill_value_11 != 100){
							if($first3){$first3 = false;} else {$text .= '、';}
							$text .= '血量'.($obj->skill_value_11/100).'倍';
						}
						if($obj->skill_value_12 != 100){
							if($first3){$first3 = false;} else {$text .= '、';}
							$text .= '攻擊力'.($obj->skill_value_12/100).'倍';
						}
					}
				}
				if($obj->skill_type == 2){
					if($first){$first = false;} else {$text .= '、';}
					$text .= $obj->detailcn;
					/*$first2 = true;
					$text .='當需要';
					$arrRequirement = array($this->getRequirement($obj->skill_value_00), $this->getRequirement($obj->skill_value_02), $this->getRequirement($obj->skill_value_04), $this->getRequirement($obj->skill_value_06), $this->getRequirement($obj->skill_value_08), $this->getRequirement($obj->skill_value_10), $this->getRequirement($obj->skill_value_12));
					$arrCount = array($obj->skill_value_01, $obj->skill_value_03, $obj->skill_value_05, $obj->skill_value_07, $obj->skill_value_09, $obj->skill_value_11, $obj->skill_value_13);
					for($i = 0; $i<sizeof($arrRequirement) ;$i++){
						if($arrRequirement[$i] != null){
							if($first2){$first2 = false;} else {$text .= '･';}
							if($arrRequirement[$i][0] != null)
								$text .= $arrRequirement[$i][0].'屬性';
							if($arrRequirement[$i][1] != null)
								$text .= $arrRequirement[$i][1].'板';
						}
					}
					$text .= '組成的NS同時發動時, 攻擊力'.($obj->skill_value_15/100).'倍';
					// value 0 2 4 6 8 10 12 -> call getRequirement()
					// value 1 3 5 7 9 11 13 -> flag
					*/
				}
				if($obj->skill_type == 3){
					if($first){$first = false;} else {$text .= '、';}
					$text .= '通關獲得經驗值'.($obj->skill_value_00/100).'倍';
				}
				if($obj->skill_type == 4){
					if($first){$first = false;} else {$text .= '、';}
					$text .= $obj->detailcn;
				}
				if($obj->skill_type == 5){
					if($first){$first = false;} else {$text .= '、';}
					$text .= $this->getElement($obj->skill_value_00).'屬性敵人造成的傷害減少'.(100-$obj->skill_value_01).'%';
				}
				break;
			case 'a':
				if($obj->skill_cate == 15 || $obj->skill_cate == 24 || $obj->skill_cate == 25 || $obj->skill_cate == 41 || $obj->skill_cate == 49){
					if($obj->skill_damage_enable == 2){
						if($obj->skill_kickback > 0){
							if($obj->skill_kickback == 100)
								$text .= 'HP變為1、';
							else
								$text .= '扣減我方'.$obj->skill_kickback.'%血量、';
						}
						$text .= '對敵方';
						if($obj->skill_type == 1)
							$text .= '單體';
						else
							$text .= '全體';
						$text .= '造成';
						if($obj->skill_power > 0)
							$text .= '自身攻擊力'.($obj->skill_power/100).'倍';
						else if($obj->skill_power_fix > 0)
							$text .= ($obj->skill_power_fix).'點';
						else if($obj->skill_power_hp_rate > 0)
							$text .= '血量'.$obj->skill_power_hp_rate.'%';
						$text .= $this->getElement($obj->skill_elem).'屬性';
						if($obj->skill_chk_def_defence === 1)
							$text .= '貫通';
						$text .= '攻擊';
						if($obj->skill_absorb > 0)
							$text .= '、並回復'.($obj->skill_absorb/100).'%血量';
						$text .= '。';
					} else {
						if($obj->skill_absorb > 0)
							$text .= '回復'.($obj->skill_absorb/100).'%血量。';
					}
				}
				switch($obj->skill_cate){
					case 2:
					case 3:
					case 4:
					case 6:
					case 9:
					case 10:
						if($obj->skill_damage_enable == 2){
							if($obj->skill_kickback > 0){
								if($obj->skill_kickback == 100)
									$text .= 'HP變為1、';
								else
									$text .= '扣減我方'.$obj->skill_kickback.'%血量、';
							}
							$text .= '對敵方';
							if($obj->skill_type == 1)
								$text .= '單體';
							else
								$text .= '全體';
							$text .= '造成';
							if($obj->skill_power > 0)
								$text .= '自身攻擊力'.($obj->skill_power/100).'倍';
							else if($obj->skill_power_fix > 0)
								$text .= ($obj->skill_power_fix).'點';
							else if($obj->skill_power_hp_rate > 0)
								$text .= '血量'.$obj->skill_power_hp_rate.'%';
							$text .= $this->getElement($obj->skill_elem).'屬性';
							if($obj->skill_chk_def_defence === 1)
								$text .= '貫通';
							$text .= '攻擊';
							if($obj->skill_absorb > 0)
								$text .= '、並回復'.($obj->skill_absorb/100).'%血量';
						}
						break;
					case 5:
						if($obj->skill_damage_enable == 2){
							if($obj->skill_kickback > 0){
								if($obj->skill_kickback == 100)
									$text .= 'HP變為1、';
								else
									$text .= '扣減我方'.$obj->skill_kickback.'%血量、';
							}
							$text .= '對敵方';
							if($obj->skill_type == 1)
								$text .= '單體';
							else
								$text .= '全體';
							$text .= $obj->value0.'屬性寵物造成自身攻擊力'.($obj->skill_power/100).'倍'.$this->getElement($obj->skill_elem).'屬性';
							if($obj->skill_chk_def_defence === 1)
								$text .= '貫通';
							$text .= '攻擊';
						}
						break;
					case 7:
						if($obj->skill_power != 0){
							if($obj->skill_kickback > 0){
								if($obj->skill_kickback == 100)
									$text .= 'HP變為1、';
								else
									$text .= '扣減我方'.$obj->skill_kickback.'%血量、';
							}
							$text .= '對敵方';
							if($obj->skill_type == 1)
								$text .= '單體';
							else
								$text .= '全體';
							$text .= '造成自身攻擊力'.($obj->skill_power/100).'倍'.$this->getElement($obj->skill_elem).'屬性';
							if($obj->skill_chk_def_defence === 1)
								$text .= '貫通';
							$text .= '攻擊。';
						}
						$text .= '稀有、殺死敵方一人 ('.$obj->value0.'%)';
						break;
					case 8:
						if($obj->skill_damage_enable == 2){
							if($obj->skill_kickback > 0){
								if($obj->skill_kickback == 100)
									$text .= 'HP變為1、';
								else
									$text .= '扣減我方'.$obj->skill_kickback.'%血量、';
							}
							$text .= '對敵方';
							if($obj->skill_type == 1)
								$text .= '單體';
							else
								$text .= '全體';
							$text .= '造成'.($obj->skill_power_fix).'點'.$this->getElement($obj->skill_elem).'屬性';
							if($obj->skill_chk_def_defence === 1)
								$text .= '貫通';
							$text .= '攻擊';
						}
						break;
					case 11:
						if($obj->skill_damage_enable == 2){
							if($obj->skill_kickback > 0){
								if($obj->skill_kickback == 100)
									$text .= 'HP變為1、';
								else
									$text .= '扣減我方'.$obj->skill_kickback.'%血量、';
							}
							$text .= '對敵方';
							if($obj->skill_type == 1)
								$text .= '單體';
							else
								$text .= '全體';
							$text .= '造成血量'.$obj->skill_power_hp_rate.'%的傷害';
						}
						break;
					case 12:
						// TODO 特殊攻擊
						$text .= $obj->detailcn;
						break;
					// case 13: status ailment (中毒)
					// case 14: status ailment (防禦力下降)
					// case 16: status ailment (傷害減少)
					// case 17: status ailment (攻擊力增加)
					// case 18: status ailment (屬性攻擊力增加)
					// case 19: status ailment (CD增加)
					// case 20: status ailment (屬性攻擊無效化)
					case 21:
						$text .= $obj->value2.'回合內、受到傷害時進行'.$this->getElement($obj->skill_elem).'屬性反擊';
						break;
					case 22:
						$text .= '1回合內、所有'.$this->getElement($obj->value2).'卡變成'.$this->getElement($obj->value3).'卡';
						break;
					case 23:
						$text .= '1回合內、所有卡變成'.$this->getElement($obj->value3).'卡';
						break;
					case 24:
						$text .= '手上所有'.$this->getElement($obj->value0).'卡變成'.$this->getElement($obj->value1).'卡';
						break;
					case 25:
						$text .= '手上所有卡隨機變化';
						break;
					case 28:
						$text .= '血量'.$obj->value0.'回復';
						break;
					case 29:
						$text .= '血量'.$obj->value0.'%回復';
						break;
					case 30:
						$text .= 'SP '.$obj->value0.'點回復';
						break;
					case 40:
						$text .= '全場都是BOOST板';
						break;
					case 41:
						// value0,1,2,3,4
						$text .= $obj->detailcn;
						break;
					// case 42: not use
					// case 43: status ailment (類攻擊力倍率)
					case 45:
						$text .= '直接傳送到終點門前';
						break;
					case 46:
						$text .= '直接打開所有？格';
						break;
					case 47:
						$text .= '血量'.$obj->value0.'%回復、我方所有寵物各種效果、異常狀態消失';
						break;
					case 49:
						// 清空放板格，設置卡
						$text .= $obj->detailcn;
						break;
					default:
						$text .= $obj->detailcn;
						break;
				}
				if(strlen($text) > 0)
					$text .= '。';
				if($obj->status_ailment1 != 0){
					$text .= $this->statusAilment($obj->getStatusAilment(1),0);
				}
				if($obj->status_ailment2 != 0){
					$text .= '、'.$this->statusAilment($obj->getStatusAilment(2),0);
				}
				if($obj->status_ailment3 != 0){
					$text .= '、'.$this->statusAilment($obj->getStatusAilment(3),0);
				}
				if($obj->status_ailment4 != 0){
					$text .= '、'.$this->statusAilment($obj->getStatusAilment(4),0);
				}


				if($obj->skill_cate == 19)
					$text .= '（最多加至9秒）';
				if($obj->skill_cate == 44)
					$text .= '除了自己, 所有隊員主動技CD數減'.$obj->value0;
				if($obj->use_sp != 0){
					$text .= ' [ffff00](SP消費:'.$obj->use_sp.')[-]';
				}
				break;
			case 'n':
				switch($obj->skill_type){
					case 1:
						$text .= '對單一敵人造成'.$this->getElement($obj->skill_element).'屬性';
						switch($obj->skill_value){
							case 100:
								$text .= '小';
								break;
							case 160:
								$text .= '中';
								break;
							case 230:
								$text .= '大';
								break;
							case 300:
								$text .= '特大';
								break;
							case 450:
								$text .= '超特大';
								break;
							case 600:
								$text .= '絕大';
								break;
							case 800:
								$text .= '超絕大';
								break;
							case 1000:
								$text .= '極大';
								break;
						}
						if($obj->skill_value_rand != 0){
							$val = $obj->skill_value + $obj->skill_value_rand;
								$text .= '～';
							switch($val){
								case 100:
									$text .= '小';
									break;
								case 160:
									$text .= '中';
									break;
								case 230:
									$text .= '大';
									break;
								case 300:
									$text .= '特大';
									break;
								case 450:
									$text .= '超特大';
									break;
								case 600:
									$text .= '絕大';
									break;
								case 800:
									$text .= '超絕大';
									break;
								case 1000:
									$text .= '極大';
									break;
							}
						}
						$text .= '傷害 ('.($obj->skill_value/100).'倍)';
						break;
					case 2:
						$text .= '對全體敵人造成'.$this->getElement($obj->skill_element).'屬性';
						switch($obj->skill_value){
							case 100:
								$text .= '小傷害 (1倍)';
								break;
							case 160:
								$text .= '中傷害 (1.6倍)';
								break;
							case 180:
								$text .= '大傷害 (1.8倍)';
								break;
							case 250:
								$text .= '特大傷害 (2.5倍)';
								break;
							case 280:
								$text .= '超特大傷害 (2.8倍)';
								break;
							case 300:
								$text .= '絕大傷害 (3倍)';
								break;
							case 400:
								$text .= '超絕大傷害 (4倍)';
								break;
							case 650:
								$text .= '極大傷害 (6.5倍)';
								break;
						}
						break;
					case 5:
						$text .= '血量'.$obj->skill_value.'%回復';
						break;
				}
				// find CRT
				preg_match('/\(CRT*\+[0-9]*％\)/', $obj->detail , $crt);
				if(sizeof($crt) > 0)
					$text .= $crt[0];
				if($obj->skill_boost_id != 0){
					$text .= ' [ff372c]■BOOST:';
					$boost = $obj->getSkillBoost()->first();
					if($boost->skill_damage_enable == 2){
						$text .= ' 對敵方';
						if($boost->skill_type == 1)
							$text .= '單體造成';
						else
							$text .= '全體造成';
						if($boost->skill_power > 0)
							$text .= '造成自身攻擊力'.($boost->skill_power/100).'倍'.$this->getElement($obj->skill_boost_elem).'屬性';
						else if($boost->skill_power_fix > 0)
							$text .= ($boost->skill_power_fix).'點';
						else if($boost->skill_power_hp_rate > 0)
							$text .= '血量'.$boost->skill_power_hp_rate.'%';
						if($boost->skill_chk_def_defence === 1)
							$text .= '貫通';
						$text .= '攻擊';
						if($obj->skill_absorb > 0)
							$text .= '、並回復'.($obj->skill_absorb/100).'%血量';
					}
					switch($boost->skill_cate){
						case 1:
							$text .= ' 血量'.$boost->skill_param_00.'%回復';
							break;
						case 2:
							$text .= ' 血量'.$boost->skill_param_00.'點回復';
							break;
						case 3:
							$text .= ' SP '.$boost->skill_param_00.'點回復';
							break;
						case 4:
							$text .= ' '.$obj->boostcn;
							break;
						case 6:
							$text .= ' '.$obj->boostcn;
							break;
						case 9:
							$text .= '我方所有隊員主動技CD數減'.$boost->skill_param_01;
							break;
					}
					if($boost->status_ailment1 != 0){
						$text .= '、'.$this->statusAilment($boost->getStatusAilment(1),$boost->status_ailment_target);
					}
					if($boost->status_ailment2 != 0){
						$text .= '、'.$this->statusAilment($boost->getStatusAilment(2),$boost->status_ailment_target);
					}
					if($boost->status_ailment3 != 0){
						$text .= '、'.$this->statusAilment($boost->getStatusAilment(3),$boost->status_ailment_target);
					}
					if($boost->status_ailment4 != 0){
						$text .= '、'.$this->statusAilment($boost->getStatusAilment(4),$boost->status_ailment_target);
					}
					$text .= '[-]';
				}
				if($obj->ability > 0){
					$text .= ' [00ffff]●特性:'.$obj->detailcn.'[-]';
				}
				break;
			case 'p':
				if($obj->skill_trap_pass_active == 2){
					if($obj->skill_trap_pass_type <= 10)
						$text .= '迴避Lv'.$obj->skill_trap_pass_type.'移動系陷阱';
					else if($obj->skill_trap_pass_type <= 20)
						$text .= '迴避Lv'.($obj->skill_trap_pass_type-10).'狀態系陷阱';
					else if($obj->skill_trap_pass_type <= 30)
						$text .= '迴避Lv'.($obj->skill_trap_pass_type-20).'環境系陷阱';
					else if($obj->skill_trap_pass_type <= 40)
						$text .= '迴避Lv'.($obj->skill_trap_pass_type-30).'傷害系陷阱';
					else if($obj->skill_trap_pass_type <= 50)
						$text .= '迴避所有Lv'.($obj->skill_trap_pass_type-40).'陷阱';
				}else if($obj->skill_powup_kind_active == 2){
					$text .= '當敵人是'.$this->getKind($obj->skill_powup_kind_type).'時、攻擊力變成'.($obj->skill_powup_kind_rate/100).'倍';
				}else if($obj->skill_counter_atk_active == 2){
					$text .= '對敵方造成'.$obj->skill_counter_atk_scale.'%'.$this->getElement($obj->skill_counter_atk_element).'屬性反擊(發動率:'.$obj->skill_counter_atk_odds.'%)';
				}else if($obj->skill_damage_recovery_active == 2){
					$text .= '當受到傷害時、'.$obj->skill_damage_recovery_odds.'%機會回復'.$obj->skill_damage_recovery_rate.'%血量';
				}else if($obj->skill_hp_full_powup_active == 2){
					$text .= '血量全滿時、攻擊力'.($obj->skill_hp_full_powup_scale/100).'倍';
				}else if($obj->skill_dying_powup_active == 2){
					$text .= '血量'.$obj->skill_dying_powup_border.'%以下時、攻擊力'.($obj->skill_dying_powup_scale/100).'倍';
				}else if($obj->skill_backatk_pass_active == 2){
					if($obj->skill_backatk_pass_rate < 0)
						$text .= '不會發生Back attack';
				}else if($obj->skill_decline_dmg_elem_active == 2){
					if($obj->skill_decline_dmg_elem_elem == 0)
						$text .= '我方所受傷害';
					else
						$text .= '我方所受'.$this->getElement($obj->skill_decline_dmg_elem_elem).'屬性傷害';
					if($obj->skill_decline_dmg_elem_rate < 100)
						$text .= '減少';
					else
						$text .= '增加';
					$text .= (100-$obj->skill_decline_dmg_elem_rate).'%';
				}else if($obj->skill_decline_dmg_kind_active == 2){
					if($obj->skill_decline_dmg_kind_kind == 0)
						$text .= '我方所受傷害';
					else
						$text .= '我方所受'.$this->getElement($obj->skill_decline_dmg_kind_kind).'屬性傷害';
					if($obj->skill_decline_dmg_kind_rate < 100)
						$text .= '減少';
					else
						$text .= '增加';
					$text .= (100-$obj->skill_decline_dmg_kind_rate).'%';
				}else if($obj->skill_boost_chance_active == 2){
					$text .= 'BOOST格出現率上升, 並有機會同時出現'.($obj->skill_boost_chance_count+1).'格';
				}else if($obj->skill_type == 1){
					$text .= $obj->detailcn;
				}else if($obj->skill_type == 3){
					$text .= '受到攻擊時, 手上每一塊'.$this->getElement($obj->skill_param_00).'板可減傷'.(100-$obj->skill_param_01).'%。成功發動減傷效果後, 相應的'.$this->getElement($obj->skill_param_00).'板隨機轉換';
				}else if($obj->skill_type == 4){
					$text .= '當攻擊是'.$obj->skill_param_01.' combo';
					if($obj->skill_param_00 == 2)
						$text .= '以上';
					$text .= ', 血量'.$obj->skill_param_03.'%回復';
				}else if($obj->skill_type == 5){
					$text .= '下一turn的攻擊時間';
					if($obj->skill_param_00 > 0)
						$text .= '增加'.$obj->skill_param_00.'秒(最大9秒)';
					else
						$text .= '減少'.($obj->skill_param_00*(-1)).'秒(最大9秒)';
				}else if($obj->skill_type == 6){
					$text .= '每1 combo能把rate';
					if($obj->skill_param_00 > 0)
						$text .= '提高'.($obj->skill_param_00/100);
					else
						$text .= '下降'.($obj->skill_param_00*(-1)/100);
				}else if($obj->skill_type == 7){
					if($obj->skill_param_00 == 100)
						$text .= '血量全滿時、並受到高於總血量的攻擊時、血量變成1';
					else
						$text .= '血量'.$obj->skill_param_00.'%時、並受到高於總血量的攻擊時、血量變成1';
				}else if($obj->skill_type == 9){
					$text .= '移動時每回合血量'.$obj->skill_param_00.'%回復';
				}else if($obj->skill_type == 10){
					$text .= '戰鬥時每回合血量'.$obj->skill_param_00.'%回復';
				}else if($obj->skill_type == 11){
					$text .= '對敵方全體造成自身攻擊力'.($obj->skill_param_01/100).'倍'.$this->getElement($obj->skill_param_00).'屬性追打';
				}else if($obj->skill_type == 13){
					$text .= $obj->detailcn;
				}else if($obj->skill_type == 14){
					$text .= $obj->skill_param_00.'種屬性或以上同時攻擊時、攻擊力'.($obj->skill_param_01/100).'倍 (同一種類的PS不會疊加)';
				}else if($obj->skill_type == 15){
					$text .= '當攻擊是'.$obj->skill_param_00.' combo或以上, 本體攻擊力'.($obj->skill_param_01/100).'倍';
				}
				break;
		}
		return $text;
	}

	function linkRaceBonus($rare, $kind, $sub_kind = 0){
		if($sub_kind != 0){
			$bonuses = LinkSystem::where('rare','=',$rare)->where('kind', $kind)->orWhere(function($query)use($rare,$sub_kind){$query->where('rare','=',$rare)->where('kind','=',$sub_kind);})->get();
		}else
			$bonuses = LinkSystem::where('rare','=',$rare)->where('kind', $kind)->get();

		$returnStr = '';
		$count = 0;
		foreach($bonuses as $bonus){
			if($count == 0 || $count == 3){
				if($count == 3)
					$returnStr .= '<br/>';
				$returnStr .= $this->getKind($bonus->kind).': ';
				if($bonus->hp > 0)
					$returnStr .= 'HP +'.$bonus->hp.' ';
				if($bonus->atk > 0)
					$returnStr .= 'ATK +'.($bonus->atk/100).'% ';
				if($bonus->crt > 0)
					$returnStr .= 'CRT +'.($bonus->crt/100).'% ';
				if($bonus->bst > 0)
					$returnStr .= 'BOOST +'.($bonus->bst/100).'% ';
			}
			$count++;
		}
		return $returnStr;
	}

	function getArea($unit){
		$enemys = Enemy::where('drop_unit_id', '=', $unit->fix_id)->get();
		$array = array();
		$array2 = array();
		$array3 = array();
		// get enemy group id
		foreach($enemys as $enemy){
			$enemygroups = EnemyGroup::where('enemy_id_1', '=', $enemy->fix_id)->orWhere('enemy_id_2', '=', $enemy->fix_id)->orWhere('enemy_id_3', '=', $enemy->fix_id)->orWhere('enemy_id_4', '=', $enemy->fix_id)->orWhere('enemy_id_5', '=', $enemy->fix_id)->orWhere('enemy_id_6', '=', $enemy->fix_id)->orWhere('enemy_id_7', '=', $enemy->fix_id)->get();
			foreach($enemygroups as $group){
				$array[] = $group->fix_id;
			}
		}
		// get quest id
		foreach ($array as $value) {
			$quests = QuestFloor::where('enemy_group_id_1', '=', $value)->orWhere('enemy_group_id_2', '=', $value)->orWhere('enemy_group_id_3', '=', $value)->orWhere('enemy_group_id_4', '=', $value)->orWhere('enemy_group_id_5', '=', $value)->orWhere('enemy_group_id_6', '=', $value)->orWhere('enemy_group_id_7', '=', $value)->orWhere('boss_group_id', '=', $value)->get();
			foreach($quests as $quest){
				$array2[] = $quest->quest_id;
			}
		}
		$array2 = array_unique($array2);
		$array2 = array_values($array2);
		// get area
		foreach($array2 as $quest_id){
			$quests = Quest::where('fix_id', '=', $quest_id)->get();
			foreach($quests as $quest){
				$array3[] = $quest->area();
			}
		}
		$array3 = array_unique($array3);
		$array3 = array_values($array3);
		return $array3;
	}

	function getCycleDay($cycleDateType){
		/*	C#  dg  num(dec -> bin)
		sun 0   1   2
		mon 1   7   128
		tue 2   6   64
		wed 3   5   32
		thr 4   4   16
		fri 5   3   8
		sat 6   2   4*/
		switch($cycleDateType){
			case 2:
				return Carbon::SUNDAY;
			case 4:
				return Carbon::SATURDAY;
			case 8:
				return Carbon::FRIDAY;
			case 16:
				return Carbon::THURSDAY;
			case 32:
				return Carbon::WEDNESDAY;
			case 64:
				return Carbon::TUESDAY;
			case 128:
				return Carbon::MONDAY;
			default:
				return 1;
		}
	}
}
?>
