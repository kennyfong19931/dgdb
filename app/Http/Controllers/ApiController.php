<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Util\FunctionUtil;
use App\Util\ImageUtil;
use App\Util\CacheUtil;
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

    public function __construct(){
        $this->cacheUtil = new CacheUtil();
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
        $output['area_element_naught'] = $data['area']->cost0*2;
        $output['area_element_life'] = $data['area']->cost6*2;
        
        foreach($data['quests'] as $quest){
            $obj = [];
            $obj['quest_id'] = $quest->fix_id;
            $obj['quest_name'] = $q->quest_name;
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
        $output['quest_requirement']['battle_chain'] = $data['quest']->battle_chain == 2 ? true : false;
        $output['quest_requirement']['enable_continue'] = $data['quest']->enable_continue == 1 ? false : true;
        $output['quest_requirement']['elem_fire'] =  $data['quest_requirement_obj']->elem_fire == 2 ? true : false;
        $output['quest_requirement']['elem_water'] =  $data['quest_requirement_obj']->elem_water == 2 ? true : false;
        $output['quest_requirement']['elem_wind'] =  $data['quest_requirement_obj']->elem_wind == 2 ? true : false;
        $output['quest_requirement']['elem_light'] =  $data['quest_requirement_obj']->elem_light == 2 ? true : false;
        $output['quest_requirement']['elem_dark'] =  $data['quest_requirement_obj']->elem_dark == 2 ? true : false;
        $output['quest_requirement']['elem_naught'] =  $data['quest_requirement_obj']->elem_naught == 2 ? true : false;
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
                $output['quest_requirement']['fix_team']['fix_unit'][$i]['unit_id'] = $data['quest_requirement']['fix_team']['fix_unit'][$i]['unit']->getApiObj();
                $output['quest_requirement']['fix_team']['fix_unit'][$i]['lv'] = $data['quest_requirement']['fix_team']['fix_unit'][$i]['lv'];
                $output['quest_requirement']['fix_team']['fix_unit'][$i]['lbs_lv'] = $data['quest_requirement']['fix_team']['fix_unit'][$i]['lbs_lv'];
                $output['quest_requirement']['fix_team']['fix_unit'][$i]['plus_hp'] = $data['quest_requirement']['fix_team']['fix_unit'][$i]['plus_hp'];
                $output['quest_requirement']['fix_team']['fix_unit'][$i]['plus_atk'] = $data['quest_requirement']['fix_team']['fix_unit'][$i]['plus_atk'];
            }
            if(isset($data['quest_requirement']['fix_team']['link_unit'][$i])){
                $output['quest_requirement']['fix_team']['link_unit'][$i]['unit_id'] = $data['quest_requirement']['fix_team']['link_unit'][$i]['unit']->getApiObj();
                $output['quest_requirement']['fix_team']['link_unit'][$i]['lv'] = $data['quest_requirement']['fix_team']['link_unit'][$i]['lv'];
                $output['quest_requirement']['fix_team']['link_unit'][$i]['lbs_lv'] = $data['quest_requirement']['fix_team']['link_unit'][$i]['lbs_lv'];
                $output['quest_requirement']['fix_team']['link_unit'][$i]['plus_hp'] = $data['quest_requirement']['fix_team']['link_unit'][$i]['plus_hp'];
                $output['quest_requirement']['fix_team']['link_unit'][$i]['plus_atk'] = $data['quest_requirement']['fix_team']['link_unit'][$i]['plus_atk'];
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
        $output['clear_unit'] = $data['quest']->clearUnit()->getApiObj();
        $output['clear_unit']['lv'] = $data['quest']->clear_unit_lv;
        if(isset($data['noData'])){
            $output['noData'] = true;
            $obj = [];
            $obj['unit'] = $data['boss']->getApiObj();
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
                $obj['unit'] = $unit['unit']->getApiObj();
                $obj['hp'] = $unit['hp'];
                $obj['atk'] = $unit['atk'];
                $obj['def'] = $unit['def'];
                $obj['cd'] = $unit['cd'];
                $obj['drop'] = $unit['drop']->getApiObj();
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
                $output['boss'][] = $obj;
            }
            foreach($data['enemy'] as $unit){
                $obj = [];
                $obj['unit'] = $unit['unit']->getApiObj();
                $obj['hp'] = $unit['hp'];
                $obj['atk'] = $unit['atk'];
                $obj['def'] = $unit['def'];
                $obj['cd'] = $unit['cd'];
                $obj['drop'] = $unit['drop']->getApiObj();
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
                            $tempArray[] = $enemy->getApiObj();
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

        return response()->json($output);
    }
    
    public function rank(){
        $output = [];

        return response()->json($output);
    }

    public function skill($type){
        return response()->json($this->cacheUtil->skill($type));
    }
    
    public function story(){
        $output = [];

        return response()->json($output);
    }
    
    public function unit($id){
        $output = [];

        return response()->json($output);
    }

    public function unitlist(){
        $unitlist = $this->cacheUtil->unitlist();
        return response()->json($unitlist);
    }
    
    public function voteResult(){
        $output = [];

        return response()->json($output);
    }
}
