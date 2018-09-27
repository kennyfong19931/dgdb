<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Util\CacheUtil;
use App\Util\FunctionUtil;
use App\Util\ImageUtil;
use App\Models\ASkill;
use App\Models\LSkill;
use App\Models\NSkill;
use App\Models\PSkill;
use App\Models\Unit;
use \Cache;
use \DB;
use Carbon\Carbon;

class ApiController extends Controller
{
    private $cacheUtil;
    private $function;
    private $imageUtil;

    public function __construct(){
        $this->cacheUtil = new CacheUtil();
        $this->function = new FunctionUtil();
        $this->imageUtil = new ImageUtil();
    }

    public function area($id){
        $output = [];
        $data = $this->cacheUtil->area($id);
        $output['area_cate_name'] = $data['area_cate_name'];
        $output['area'] = $data['area']->area_name;
        $output['area_res_map'] = $data['area']->res_map;
        $output['area_res_map_icon'] = $data['area']->res_map_icon;
        $output['area_res_icon_key'] = $data['area']->res_icon_key;
        $output['area_res_icon_box'] = $data['area']->res_icon_box;
        $output['area_url'] = $data['area']->area_url;
        $output['area_element_fire'] = $data['area']->cost1*2;
        $output['area_element_water'] = $data['area']->cost2*2;
        $output['area_element_wind'] = $data['area']->cost3*2;
        $output['area_element_light'] = $data['area']->cost4*2;
        $output['area_element_dark'] = $data['area']->cost5*2;
        $output['area_element_none'] = $data['area']->cost0*2;
        $output['area_element_life'] = $data['area']->cost6*2;
        
        foreach($data['quests'] as $quest){
            $obj = [];
            $obj['quest_id'] = $quest->fix_id;
            $obj['quest_name'] = $quest->quest_name;
            $obj['quest_stamina'] = $quest->quest_stamina;
            $obj['quest_ticket'] = $quest->quest_ticket;
            $obj['quest_key'] = $quest->quest_key;
            $obj['floor_count'] = $quest->floor_count;
            $output['quest'][] = $obj;
        }

        return response()->json($output);
    }
    
    public function quest($id){
        $statusAilmentRegex = '/alt="(.*)"\/>(.*)/m';

        $output = [];
        $data = $this->cacheUtil->quest($id);
        // basic info
        $output['area_cate_name'] = $data['area_cate_name'];
        $output['area_id'] = $data['area_id'];
        $output['area_name'] = $data['area_name'];
        $output['quest_name'] = $data['quest']->quest_name;

        // quest_requirement
		if($data['quest']->battle_chain == 2){
			$output['quest_requirement']['battle_chain'] = true;
		}
		if($data['quest']->enable_continue == 1){
			$output['quest_requirement']['enable_continue'] = false;
		}
		if(isset($data['quest_requirement_obj'])){
			$output['quest_requirement']['elem_fire'] =  $data['quest_requirement_obj']->elem_fire == 2 ? true : false;
			$output['quest_requirement']['elem_water'] =  $data['quest_requirement_obj']->elem_water == 2 ? true : false;
			$output['quest_requirement']['elem_wind'] =  $data['quest_requirement_obj']->elem_wind == 2 ? true : false;
			$output['quest_requirement']['elem_light'] =  $data['quest_requirement_obj']->elem_light == 2 ? true : false;
			$output['quest_requirement']['elem_dark'] =  $data['quest_requirement_obj']->elem_dark == 2 ? true : false;
			$output['quest_requirement']['elem_none'] =  $data['quest_requirement_obj']->elem_naught == 2 ? true : false;
			$output['quest_requirement']['num_elem'] =  $data['quest_requirement_obj']->num_elem == 1 ? true : false;
			$output['quest_requirement']['kind_human'] =  $data['quest_requirement_obj']->kind_human == 2 ? true : false;
			$output['quest_requirement']['kind_fairy'] =  $data['quest_requirement_obj']->kind_fairy == 2 ? true : false;
			$output['quest_requirement']['kind_demon'] =  $data['quest_requirement_obj']->kind_demon == 2 ? true : false;
			$output['quest_requirement']['kind_dragon'] =  $data['quest_requirement_obj']->kind_dragon == 2 ? true : false;
			$output['quest_requirement']['kind_machine'] =  $data['quest_requirement_obj']->kind_machine == 2 ? true : false;
			$output['quest_requirement']['kind_beast'] =  $data['quest_requirement_obj']->kind_beast == 2 ? true : false;
			$output['quest_requirement']['kind_god'] =  $data['quest_requirement_obj']->kind_god == 2 ? true : false;
			$output['quest_requirement']['kind_egg'] =  $data['quest_requirement_obj']->kind_egg == 2 ? true : false;
			$output['quest_requirement']['num_kind'] =  $data['quest_requirement_obj']->num_kind == 1 ? true : false;
			$output['quest_requirement']['num_unit'] =  $data['quest_requirement_obj']->num_unit;
			$output['quest_requirement']['much_name'] =  $data['quest_requirement_obj']->much_name;
			$output['quest_requirement']['limit_cost'] =  $data['quest_requirement_obj']->limit_cost;
			$output['quest_requirement']['limit_cost_total'] =  $data['quest_requirement_obj']->limit_cost_total;
			$output['quest_requirement']['limit_unit_lv'] =  $data['quest_requirement_obj']->limit_unit_lv;
			$output['quest_requirement']['limit_unit_lv_total'] =  $data['quest_requirement_obj']->limit_unit_lv_total;
			$output['quest_requirement']['limit_rank'] =  $data['quest_requirement_obj']->limit_rank;
			$output['quest_requirement']['limit_cost'] =  $data['quest_requirement_obj']->limit_cost;
			for($i = 0; $i < 5; $i++){
				if(isset($data['quest_requirement']['fix_team']['fix_unit'][$i])){
					$output['quest_requirement']['fix_team']['fix_unit'][$i]['unit'] = $this->function->getUnitApiObj($data['quest_requirement']['fix_team']['fix_unit'][$i]['unit']);
					$output['quest_requirement']['fix_team']['fix_unit'][$i]['lv'] = $data['quest_requirement']['fix_team']['fix_unit'][$i]['lv'];
					$output['quest_requirement']['fix_team']['fix_unit'][$i]['plus_hp'] = $data['quest_requirement']['fix_team']['fix_unit'][$i]['plus_hp'];
					$output['quest_requirement']['fix_team']['fix_unit'][$i]['plus_atk'] = $data['quest_requirement']['fix_team']['fix_unit'][$i]['plus_atk'];
				}
				if(isset($data['quest_requirement']['fix_team']['link_unit'][$i])){
					$output['quest_requirement']['fix_team']['link_unit'][$i]['unit'] = $this->function->getUnitApiObj($data['quest_requirement']['fix_team']['link_unit'][$i]['unit']);
					$output['quest_requirement']['fix_team']['link_unit'][$i]['lv'] = $data['quest_requirement']['fix_team']['link_unit'][$i]['lv'];
					$output['quest_requirement']['fix_team']['link_unit'][$i]['plus_hp'] = $data['quest_requirement']['fix_team']['link_unit'][$i]['plus_hp'];
					$output['quest_requirement']['fix_team']['link_unit'][$i]['plus_atk'] = $data['quest_requirement']['fix_team']['link_unit'][$i]['plus_atk'];
				}
			}
		}

        // quest detail
        $output['story'] = $data['quest']->story;
        if(isset($data['storycn']))
            $output['storycn'] = $data['storycn'];
        $output['quest_stamina'] = $data['quest']->quest_stamina;
        $output['quest_ticket'] = $data['quest']->quest_ticket;
        $output['quest_key'] = $data['quest']->quest_key;
        if(isset($data['area_key']))
            $output['area_key'] = $data['area_key'];
        $output['clear_money'] = $data['quest']->clear_money;
        $output['clear_exp'] = $data['quest']->clear_exp;
        $output['clear_stone'] = $data['quest']->clear_stone;
        $output['clear_link_point'] = ($data['quest']->clear_link_point)/100;
        $output['clear_unit'] = $this->function->getUnitApiObj($data['quest']->clearUnit());
        $output['clear_unit']['lv'] = $data['quest']->clear_unit_lv;
        if(isset($data['noData'])){
            $output['noData'] = true;
            $obj = [];
            $obj['unit'] = $this->function->getUnitApiObj($data['boss']);
            if(isset($data['boss']['ability'])){
                foreach($data['boss']['ability'] as $ability){
                    $temp = [];
                    $temp['name'] = $ability['name'];
                    $temp['detail'] = $ability['detail'];
                    $temp['detailcn'] = $ability->getDetailCn();
                    $obj['ability'][] = $temp;
                }
            }
            $output['boss'][] = $obj;
        } else {
            $output['noData'] = false;
            foreach($data['boss'] as $unit){
                $obj = [];
                $obj['unit'] = $this->function->getUnitApiObj($unit['unit']);
                $obj['hp'] = $unit['hp'];
                $obj['atk'] = $unit['atk'];
                $obj['def'] = $unit['def'];
                $obj['cd'] = $unit['cd'];
                $obj['drop'] = $this->function->getUnitApiObj($unit['drop']);
                if(isset($unit['ability'])){
                    foreach($unit['ability'] as $ability){
                        $temp = [];
                        $temp['name'] = $ability['name'];
                        $temp['detail'] = $ability['detail'];
                        $temp['detailcn'] = $ability->getDetailCn();
                        $obj['ability'][] = $temp;
                    }
                }
                if(isset($unit['act_first'])){
                    $temp = $unit['act_first'];
                    if(isset($temp['status_ailment'])){
                        $tempStatusAilment = $temp['status_ailment'];
                        $temp['status_ailment'] = [];
                        foreach($tempStatusAilment as $statusAilment){
                            $matches = [];
                            preg_match_all($statusAilmentRegex, $statusAilment, $matches, PREG_SET_ORDER, 0);
                            if(sizeof($matches) > 0){
                                $tempObj = [];
                                $tempObj['icon'] = $matches[0][1];
                                $tempObj['detail'] = $matches[0][2];
                                $temp['status_ailment'][] = $tempObj;
                            } else {
                                $tempObj = [];
                                $tempObj['detail'] = $statusAilment;
                                $temp['status_ailment'][] = $tempObj;
                            }
                        }
                    }
                    $obj['act_first'] = $temp;
                }
                if(isset($unit['act_dead'])){
                    $temp = $unit['act_dead'];
                    if(isset($temp['status_ailment'])){
                        $tempStatusAilment = $temp['status_ailment'];
                        $temp['status_ailment'] = [];
                        foreach($tempStatusAilment as $statusAilment){
                            $matches = [];
                            preg_match_all($statusAilmentRegex, $statusAilment, $matches, PREG_SET_ORDER, 0);
                            if(sizeof($matches) > 0){
                                $tempObj = [];
                                $tempObj['icon'] = $matches[0][1];
                                $tempObj['detail'] = $matches[0][2];
                                $temp['status_ailment'][] = $tempObj;
                            } else {
                                $tempObj = [];
                                $tempObj['detail'] = $statusAilment;
                                $temp['status_ailment'][] = $tempObj;
                            }
                        }
                    }
                    $obj['act_dead'] = $temp;
                }
                for($i = 1; $i < 9; $i++){
                    if(isset($unit['act_table'.$i])){
                        $temp = [];
                        $temp['timing_type'] = $unit['act_table'.$i]['timing_type'];
                        $temp['timing_param1'] = $unit['act_table'.$i]['timing_param1'];
                        $temp['action_type'] = $unit['act_table'.$i]['action_type'];
                        foreach($unit['act_table'.$i]['moves'] as $move){
                            if(isset($move['status_ailment'])){
                                $tempStatusAilment = $move['status_ailment'];
                                $move['status_ailment'] = [];
                                foreach($tempStatusAilment as $statusAilment){
                                    $matches = [];
                                    preg_match_all($statusAilmentRegex, $statusAilment, $matches, PREG_SET_ORDER, 0);
                                    if(sizeof($matches) > 0){
                                        $tempObj = [];
                                        $tempObj['icon'] = $matches[0][1];
                                        $tempObj['detail'] = $matches[0][2];
                                        $move['status_ailment'][] = $tempObj;
                                    } else {
                                        $tempObj = [];
                                        $tempObj['detail'] = $statusAilment;
                                        $move['status_ailment'][] = $tempObj;
                                    }
                                }
                            }
                            $temp['moves'][] = $move;
                        }
                        $obj['act_table'][] = $temp;
                    }
                }
                $output['boss'][] = $obj;
            }
            foreach($data['enemy'] as $unit){
                $obj = [];
                $obj['unit'] = $this->function->getUnitApiObj($unit['unit']);
                $obj['hp'] = $unit['hp'];
                $obj['atk'] = $unit['atk'];
                $obj['def'] = $unit['def'];
                $obj['cd'] = $unit['cd'];
                $obj['drop'] = $this->function->getUnitApiObj($unit['drop']);
                if(isset($unit['ability'])){
                    foreach($unit['ability'] as $ability){
                        $temp = [];
                        $temp['name'] = $ability['name'];
                        $temp['detail'] = $ability['detail'];
                        $temp['detailcn'] = $ability->getDetailCn();
                        $obj['ability'][] = $temp;
                    }
                }
                if(isset($unit['act_first'])){
                    $temp = $unit['act_first'];
                    if(isset($temp['status_ailment'])){
                        $tempStatusAilment = $temp['status_ailment'];
                        $temp['status_ailment'] = [];
                        foreach($tempStatusAilment as $statusAilment){
                            $matches = [];
                            preg_match_all($statusAilmentRegex, $statusAilment, $matches, PREG_SET_ORDER, 0);
                            if(sizeof($matches) > 0){
                                $tempObj = [];
                                $tempObj['icon'] = $matches[0][1];
                                $tempObj['detail'] = $matches[0][2];
                                $temp['status_ailment'][] = $tempObj;
                            } else {
                                $tempObj = [];
                                $tempObj['detail'] = $statusAilment;
                                $temp['status_ailment'][] = $tempObj;
                            }
                        }
                    }
                    $obj['act_first'] = $temp;
                }
                if(isset($unit['act_dead'])){
                    $temp = $unit['act_dead'];
                    if(isset($temp['status_ailment'])){
                        $tempStatusAilment = $temp['status_ailment'];
                        $temp['status_ailment'] = [];
                        foreach($tempStatusAilment as $statusAilment){
                            $matches = [];
                            preg_match_all($statusAilmentRegex, $statusAilment, $matches, PREG_SET_ORDER, 0);
                            if(sizeof($matches) > 0){
                                $tempObj = [];
                                $tempObj['icon'] = $matches[0][1];
                                $tempObj['detail'] = $matches[0][2];
                                $temp['status_ailment'][] = $tempObj;
                            } else {
                                $tempObj = [];
                                $tempObj['detail'] = $statusAilment;
                                $temp['status_ailment'][] = $tempObj;
                            }
                        }
                    }
                    $obj['act_dead'] = $temp;
                }
                for($i = 1; $i < 9; $i++){
                    if(isset($unit['act_table'.$i])){
                        $temp = [];
                        $temp['timing_type'] = $unit['act_table'.$i]['timing_type'];
                        $temp['timing_param1'] = $unit['act_table'.$i]['timing_param1'];
                        $temp['action_type'] = $unit['act_table'.$i]['action_type'];
                        foreach($unit['act_table'.$i]['moves'] as $move){
                            if(isset($move['status_ailment'])){
                                $tempStatusAilment = $move['status_ailment'];
                                $move['status_ailment'] = [];
                                foreach($tempStatusAilment as $statusAilment){
                                    $matches = [];
                                    preg_match_all($statusAilmentRegex, $statusAilment, $matches, PREG_SET_ORDER, 0);
                                    if(sizeof($matches) > 0){
                                        $tempObj = [];
                                        $tempObj['icon'] = $matches[0][1];
                                        $tempObj['detail'] = $matches[0][2];
                                        $move['status_ailment'][] = $tempObj;
                                    } else {
                                        $tempObj = [];
                                        $tempObj['detail'] = $statusAilment;
                                        $move['status_ailment'][] = $tempObj;
                                    }
                                }
                            }
                            $temp['moves'][] = $move;
                        }
                        $obj['act_table'.$i] = $temp;
                    }
                }
                $output['enemy'][] = $obj;
            }
            foreach($data['floors'] as $floor){
                $obj = [];
                for($i = 1; $i < 8; $i++){
                    if(isset($floor[$i]['enemy'])){
                        $tempArray = [];
                        foreach($floor[$i]['enemy'] as $enemy){
                            $tempArray[] = $this->function->getUnitApiObj($enemy);
                        }
                        $obj[$i]['enemy'] = $tempArray;
                    }
                    if(isset($floor[$i]['trap'])){
                        $tempArray = [];
                        foreach($floor[$i]['trap'] as $trap){
                            if($trap['trap_type'] == 0)
                                continue;
                            $tempObj = [];
                            $tempObj['trap_type'] = $trap['trap_type'];
                            $tempObj['name'] = $trap['name'];
                            $tempObj['res_panel'] = $trap['res_panel'];
                            $tempObj['detail'] = $trap['detail'];
                            $tempObj['effective_type'] = $trap['effective_type'];
                            $tempObj['effective_value'] = $trap['effective_value'];
                            $tempArray[] = $tempObj;
                        }
                        $obj[$i]['trap'] = $tempArray;
                    }
                    if(isset($floor[$i]['money'])){
                        $obj[$i]['money']['min'] = $floor[$i]['money']['min']['effective_value'];
                        $obj[$i]['money']['max'] = $floor[$i]['money']['max']['effective_value'];
                        $obj[$i]['money']['icon'] = $floor[$i]['money']['icon'];
                    }
                }
                $output['floors'][] = $obj;
            }
        }

        return response()->json($output);
    }
    
    public function questlist(){
        $output = [];
        $data = $this->cacheUtil->questlist();
        for($i = 1; $i <11; $i++){
            $objCate = [];
            $areaCate = $data['areaCate'][$i];
            foreach($areaCate as $cate){
                $objAreaCate = [];
                $objAreaCate['area_cate_name'] = $cate->area_cate_name;
                $objAreaCate['area_cate_sort'] = $cate->questlist_sort;
                $objAreaCate['area_cate_type'] = $cate->area_cate_type;
                if(isset($data['areaById'][$cate->fix_id]) && sizeof($data['areaById'][$cate->fix_id]) > 0){
                    foreach($data['areaById'][$cate->fix_id] as $currentArea){
                        $area = [];
                        $area['area_id'] = $currentArea[0]->fix_id;
                        $area['area_name'] = $currentArea[0]->area_name;
                        $area['area_name_eng'] = $currentArea[0]->area_name_eng;
                        $area['questlist_sort'] = $currentArea[0]->questlist_sort;
                        $area['boss'] = $this->function->getUnitApiObj($currentArea[1]);
                        $objAreaCate['areas'][] = $area;
                    }
                }
                $objCate[] = $objAreaCate;
            }
            $output[$i] = $objCate;
        }
        return response()->json($output);
    }
    
    public function rank(){
        $output = [];
        $data = $this->cacheUtil->rank();
        foreach($data['rank'] as $rank){
            $obj = [];
            $obj['rank'] = $rank->fix_id;
            $obj['exp_next'] = $rank->exp_next;
            $obj['exp_next_total'] = $rank->exp_next_total;
            $obj['stamina'] = $rank->stamina;
            $obj['friend_max'] = $rank->friend_max;
            $obj['unit_max'] = $rank->unit_max;
            $obj['party_cost'] = $rank->party_cost;
            $output[] = $obj;
        }
        return response()->json($output);
    }

    public function skill($type){
        return response()->json($this->cacheUtil->skill($type));
    }
    
    public function story(){
        return response()->json($this->cacheUtil->story());
    }
    
    public function unit($id){
        $output = [];
        $data = $this->cacheUtil->unit($id);

        $output['id'] = $data['unit']->draw_id;
        $output['name'] = $data['unit']->name;
        $output['image_large'] = $this->imageUtil->getUnitLarge($data['unit']->draw_id);
        $output['image_large_size'] = $data['unit']->size;
        $output['image_icon'] = $this->imageUtil->getIconLink($this->function->getTriId($data['unit']->draw_id));
        $output['detail'] = $data['unit']->detail;
		
        if(isset($data['detailcn']))
			$output['detailcn'] = $data['detailcn'];
        
        $output['rare'] = $data['unit']->rare;
        $output['rarity'] = $data['unit']->rarity;
        $output['element'] = $data['unit']->element;
        $output['kind'] = $data['unit']->kind;
        $output['sub_kind'] = $data['unit']->sub_kind;
        $output['party_cost'] = $data['unit']->party_cost;
        $output['level_min'] = $data['unit']->level_min;
        $output['level_max'] = $data['unit']->level_max;
        $output['base_hp_min'] = $data['unit']->base_hp_min;
        $output['base_hp_max'] = $data['unit']->base_hp_max;
        $output['base_hp_curve'] = $data['unit']->base_hp_curve;
        $output['base_attack_min'] = $data['unit']->base_attack_min;
        $output['base_attack_max'] = $data['unit']->base_attack_max;
        $output['base_attack_curve'] = $data['unit']->base_attack_curve;
        $output['exp_total'] = $data['unit']->exp_total;
        $output['exp_total_curve'] = $data['unit']->exp_total_curve;
        $output['sales_min'] = $data['unit']->sales_min;
        $output['sales_max'] = $data['unit']->sales_max;
        $output['sales_curve'] = $data['unit']->sales_curve;
        $output['sales_unitpoint'] = $data['unit']->sales_unitpoint;
        $output['blend_exp_min'] = $data['unit']->blend_exp_min;
        $output['blend_exp_max'] = $data['unit']->blend_exp_max;
        $output['blend_exp_curve'] = $data['unit']->blend_exp_curve;
        foreach(explode(' ', $data['unit']->series) as $s){
            $output['series'][] = $s;
        }

        // LS
        if($data['unit']->skill_leader != 0){
            $output['ls']['name'] = $data['lsName'];
            $output['ls']['detail'] = $data['lsDetail'];
            $output['ls']['detailcn'] = $data['lsDetailCn'];
        }

        // AS
        if($data['unit']->skill_limitbreak != 0){
            $output['as']['name'] = $data['asName'];
            $output['as']['detail'] = $data['asDetail'];
            $output['as']['detailcn'] = $data['asDetailCn'];
            $output['as']['min'] = $data['asMin'];
            $output['as']['max'] = $data['asMax'];
            foreach($data['sameAS'] as $temp){
                $output['as']['same'][] = $this->function->getUnitApiObj($temp);
            }
        }

        // NS1
        $output['ns1']['name'] = $data['ns1Name'];
        $output['ns1']['detail'] = $data['ns1Detail'];
        $output['ns1']['detailcn'] = $data['ns1DetailCn'];
        $output['ns1']['card'] = $data['ns1Card'];

        // NS2
        if($data['unit']->skill_active1 != 0){
            $output['ns2']['name'] = $data['ns2Name'];
            $output['ns2']['detail'] = $data['ns2Detail'];
            $output['ns2']['detailcn'] = $data['ns2DetailCn'];
            $output['ns2']['card'] = $data['ns2Card'];
        }

        // PS
        if($data['unit']->skill_passive != 0){
            $output['ps']['name'] = $data['psName'];
            $output['ps']['detail'] = $data['psDetail'];
            $output['ps']['detailcn'] = $data['psDetailCn'];
        }

        // Link
        if($data['unit']->link_enable == 2){
            $output['link']['hp'] = $data['link_hp'];
            $output['link']['atk'] = $data['link_atk'];
            $output['link']['race_bouns'] = $data['race_bouns'];
            if($data['unit']->link_skill_active != 0){
                $output['link']['lns']['name'] = $data['lnsName'];
                $output['link']['lns']['detail'] = $data['lnsDetail'];
                $output['link']['lns']['detailcn'] = $data['lnsDetailCn'];
                $output['link']['lns']['min'] = $data['lnsOdds']/100;
                $output['link']['lns']['max'] = ($data['lnsOdds']*2 > 10000 ? 100 : $data['lnsOdds']*2/100);
            }
            if($data['unit']->link_skill_passive != 0){
                $output['link']['lps']['name'] = $data['lpsName'];
                $output['link']['lps']['detail'] = $data['lpsDetail'];
                $output['link']['lps']['detailcn'] = $data['lpsDetailCn'];
            }
            foreach($data['linkPart'] as $temp){
                if($temp->fix_id > 0)
                    $output['link']['link_unit'][] = $this->function->getUnitApiObj($temp);
            }
            $output['link']['link_money'] = $data['unit']->link_money;
            foreach($data['delLinkPart'] as $temp){
                if($temp->fix_id > 0)
                    $output['link']['del_link_unit'][] = $this->function->getUnitApiObj($temp);
            }
            $output['link']['del_link_money'] = $data['unit']->link_del_money;
        }

        // limitover
        if($data['limit_over_max'] !== 0){
            $output['limit_over']['limit_grow'] = $data['limit_grow'];
            $output['limit_over']['max'] = $data['limit_over_max'];
            $output['limit_over']['max_hp'] = $data['limit_over_max_hp'];
            $output['limit_over']['max_atk'] = $data['limit_over_max_atk'];
            $output['limit_over']['max_cost'] = $data['limit_over_max_cost'];
            $output['limit_over']['max_charm'] = $data['limit_over_max_charm'];
            $output['limit_over']['unitpoint'] = $data['unit']->limit_over_unitpoint;
        }
        
        // evos
        if(sizeof($data['evos']) > 0){
            foreach($data['evos'] as $temp){
                $output['evos'][] = $this->function->getUnitApiObj($temp->partPre());
            }
        }
        if(sizeof($data['evoFrom'])){
            $output['evoFrom']['part_pre'] = $this->function->getUnitApiObj($data['evoFrom']->partPre());
            for ($i = 1; $i < 5; $i++){
                if($data['evoFrom']->part($i)->fix_id > 0){
                    $output['evoFrom']['part'][] = $this->function->getUnitApiObj($data['evoFrom']->part($i));
                }
            }
            $output['evoFrom']['part_after'] = $this->function->getUnitApiObj($data['evoFrom']->partAfter());
        }
        if(!is_null($data['evoTo'])){
            $output['evoTo']['part_pre'] = $this->function->getUnitApiObj($data['evoTo']->partPre());
            for ($i = 1; $i < 5; $i++){
                if($data['evoTo']->part($i)->fix_id > 0){
                    $output['evoTo']['part'][] = $this->function->getUnitApiObj($data['evoTo']->part($i));
                }
            }
            $output['evoTo']['part_after'] = $this->function->getUnitApiObj($data['evoTo']->partAfter());
            $output['evoTo']['friend_level'] = $data['evoTo']->friend_level;
            $output['evoTo']['friend_kind'] = $data['evoTo']->friend_kind;
            $output['evoTo']['friend_elem'] = $data['evoTo']->friend_elem;
            $output['evoTo']['money'] = $data['evoTo']->money;
            $output['evoTo']['unitpoint'] = $data['unit']->evol_unitpoint;
            $output['evoTo']['quest_id'] = $data['evoTo']->quest()->fix_id;
            $output['evoTo']['quest_name'] = $data['evoTo']->quest()->quest_name;
        }

        // drop area
        foreach($data['areas'] as $area){
            $obj = [];
            $obj['area_id'] = $area->fix_id;
            $obj['area_name'] = $area->area_name;
            $output['drop_area'][] = $obj;
        }

        return response()->json($output);
    }

    public function unitlist(){
        $unitlist = $this->cacheUtil->unitlist();
        return response()->json($unitlist);
    }
    
    public function voteResult($id){
        $output = [];
        $data = $this->cacheUtil->voteResult($id);
        switch($id){
            case 8:
                $cat = ['all','fire','water','wind','light','dark','none'];
                foreach($cat as $c){
                    $array = [];
                    foreach($data[$c] as $unit){
                        $obj = [];
                        $obj['rank'] = $unit['rank'];
                        $obj['unit'] = $this->function->getUnitApiObj($unit['unit']);
						$obj['type'] = $unit['type'];
						$obj['change'] = $unit['change'];
                        $array[] = $obj;
                    }
                    $output[$c] = $array;
                }
                break;
            case 13:
                $cat = ['fav','tsundere','wife','cook'];
                foreach($cat as $c){
                    $array = [];
                    foreach($data[$c] as $unit){
                        $obj = [];
                        $obj['rank'] = $unit['rank'];
                        $obj['unit'] = $this->function->getUnitApiObj($unit['unit']);
						$obj['type'] = $unit['type'];
						$obj['change'] = $unit['change'];
                        $array[] = $obj;
                    }
                    $output[$c] = $array;
                }
                break;
            default:
                foreach($data as $unit){
                    $obj = [];
                    $obj['rank'] = $unit['rank'];
                    $obj['unit'] = $this->function->getUnitApiObj($unit['unit']);
                    $obj['type'] = $unit['type'];
                    $obj['change'] = $unit['change'];
                    $output[] = $obj;
                }
                break;
        }
        return response()->json($output);
    }
}
