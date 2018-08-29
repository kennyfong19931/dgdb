<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
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
    public function unitlist(){
        $imageUtil = new ImageUtil;
        $function = new FunctionUtil;
        $key = 'blade_unitlist';
        if (Cache::has($key)){
            $unitlist = Cache::get($key);
        } else {
            $unitlist = DB::table('unit')->select('fix_id','name','draw_id','element','kind','sub_kind','rare','series','link_enable')->where('fix_id','!=','0')->orderBy('draw_id')->get();
            foreach ($unitlist as $unit) {
                $unit->image = $imageUtil->getIconFlicker($function->getTriId($unit->draw_id));
            }
            // cache
            $expiresAt = new Carbon('next friday');
            Cache::put($key, $unitlist, $expiresAt);
        }
        return response()->json($unitlist);
    }

    public function skill($type){
        $imageUtil = new ImageUtil;
        $function = new FunctionUtil;
        $key = 'api_skill'.$type;
        if (Cache::has($key)){
            $data = Cache::get($key);
        } else {
            switch($type){
                case 'n':
                case 'ln':
                    $skills = NSkill::where('fix_id','!=','0')->get();
                    foreach($skills as $skill){
                        $obj = new \stdClass;
                        if($type == 'ln')
                            $obj->units = $skill->minLinkUnits();
                        else
                            $obj->units = $skill->minUnits();
                        if(sizeof($obj->units) == 0){continue;}
                        $obj->name = $skill->name;
                        $obj->detail = $skill->detail;
                        $obj->detailCn = $skill->getDetailCn();
                        foreach ($obj->units as $unit) {
                            $unit->image = $imageUtil->getIconFlicker($function->getTriId($unit->draw_id));
                        }
                        if($type == 'n'){
                            $cardDesc = "";
                            $cards = $skill->getCard();
                            for($i = 4; $i >= 0; $i--){
                                if($cards[$i] == 0)
                                    unset($cards[$i]);
                                else
                                    $cardDesc .= $function->getElement($cards[$i])."板 ";
                            }
                            $obj->card = $cards;
                            $cardDesc .= sizeof($obj->card)."板 ";
                            if(sizeof(array_unique($obj->card)) > 1)
                                $cardDesc .= "雜色";
                            else
                                $cardDesc .= "單色";
                            $obj->cardDesc = $cardDesc;
                        }
                        switch($skill->skill_type){
                            case 1:
                                $data['skill'][1][] = $obj;
                                break;
                            case 2:
                                $data['skill'][2][] = $obj;
                                break;
                            case 5:
                                $data['skill'][3][] = $obj;
                                break;
                        }
                    }
                    $data['status'] = 1;
                    break;
                case 'l':
                    $skills = LSkill::where('fix_id','!=','0')->get();
                    foreach($skills as $skill){
                        $obj = new \stdClass;
                        $obj->units = $skill->minUnits();
                        if(sizeof($obj->units) == 0){continue;}
                        $obj->name = $skill->name;
                        $obj->detail = $skill->detail;
                        $obj->detailCn = $skill->getDetailCn();
                        foreach ($obj->units as $unit) {
                            $unit->image = $imageUtil->getIconFlicker($function->getTriId($unit->draw_id));
                        }
                        if($skill->skill_powup_elem_status == 1 || $skill->skill_powup_kind_status == 1)
                            $data['skill'][1][] = $obj;
                        elseif ($skill->skill_powup_elem_status == 2 || $skill->skill_powup_kind_status == 2)
                            $data['skill'][2][] = $obj;
                        elseif ($skill->skill_powup_elem_status == 3 || $skill->skill_powup_kind_status == 3)
                            $data['skill'][3][] = $obj;
                        elseif ($skill->skill_follow_atk_active == 2 )
                            $data['skill'][4][] = $obj;
                        elseif ($skill->skill_decline_dmg_active == 2)
                            $data['skill'][5][] = $obj;
                        elseif ($skill->skill_recovery_move_active == 2 || $skill->skill_recovery_battle_active == 2)
                            $data['skill'][6][] = $obj;
                        elseif ($skill->skill_quick_time_active == 2)
                            $data['skill'][7][] = $obj;
                        elseif ($skill->skill_recovery_support_active == 2)
                            $data['skill'][8][] = $obj;
                        elseif ($skill->skill_recovery_atk_active == 2)
                            $data['skill'][9][] = $obj;
                        elseif ($skill->skill_recovery_battle_active == 2)
                            $data['skill'][10][] = $obj;
                        elseif ($skill->skill_hpfull_powup_active == 2 || $skill->skill_hpdown_powup_active == 2)
                            $data['skill'][11][] = $obj;
                        elseif ($skill->skill_mekuri_powup_active == 2)
                            $data['skill'][12][] = $obj;
                        elseif ($skill->skill_funbari_active == 2)
                            $data['skill'][13][] = $obj;
                        elseif ($skill->skill_hpfull_guard_active == 2)
                            $data['skill'][14][] = $obj;
                        elseif ($skill->skill_initiative_atk_active == 2)
                            $data['skill'][15][] = $obj;
                        elseif ($skill->skill_transform_card_active == 2)
                            $data['skill'][16][] = $obj;
                        elseif ($skill->skill_damageup_color_active == 2 || $skill->skill_damageup_hands_active == 2)
                            $data['skill'][17][] = $obj;
                        else{
                            switch($skill->skill_type){
                                case 2:
                                    $data['skill'][17][] = $obj;
                                    break;
                                case 3:
                                    $data['skill'][18][] = $obj;
                                    break;
                                case 5:
                                    $data['skill'][19][] = $obj;
                                    break;
                            }
                        }
                    }
                    $data['status'] = 1;
                    break;
                case 'a':
                    $skills = ASkill::where('fix_id','!=','0')->get();
                    foreach($skills as $skill){
                        $obj = new \stdClass;
                        $obj->units = $skill->minUnits();
                        if(sizeof($obj->units) == 0){continue;}
                        $obj->name = $skill->name;
                        $obj->detail = $skill->detail;
                        $obj->detailCn = $skill->getDetailCn();
                        foreach ($obj->units as $unit) {
                            $unit->image = $imageUtil->getIconFlicker($function->getTriId($unit->draw_id));
                        }
                        switch($skill->skill_cate){
                            case 2:
                                $data['skill'][1][] = $obj;
                                break;
                            case 4:
                                $data['skill'][2][] = $obj;
                                break;
                            case 5:
                                $data['skill'][3][] = $obj;
                                break;
                            case 6:
                            case 9:
                                $data['skill'][4][] = $obj;
                                break;
                            case 7:
                                $data['skill'][5][] = $obj;
                                break;
                            case 8:
                            case 11:
                                $data['skill'][6][] = $obj;
                                break;
                            case 3:
                            case 10:
                                $data['skill'][7][] = $obj;
                                break;
                            case 12:
                                $data['skill'][8][] = $obj;
                                break;
                            case 13:
                                $data['skill'][9][] = $obj;
                                break;
                            case 14:
                                $data['skill'][10][] = $obj;
                                break;
                            case 15:
                                $data['skill'][11][] = $obj;
                                break;
                            case 16:
                                $data['skill'][12][] = $obj;
                                break;
                            case 17:
                            case 18:
                            case 40:
                            case 43:
                                $data['skill'][13][] = $obj;
                                break;
                            case 19:
                                $data['skill'][14][] = $obj;
                                break;
                            case 20:
                                $data['skill'][15][] = $obj;
                                break;
                            case 21:
                                $data['skill'][16][] = $obj;
                                break;
                            case 22:
                            case 23:
                            case 24:
                            case 25:
                            case 41:
                                $data['skill'][17][] = $obj;
                                break;
                            case 28:
                                $data['skill'][18][] = $obj;
                                break;
                            case 29:
                                $data['skill'][19][] = $obj;
                                break;
                            case 30:
                                $data['skill'][20][] = $obj;
                                break;
                            case 44:
                                $data['skill'][21][] = $obj;
                                break;
                            case 45:
                                $data['skill'][22][] = $obj;
                                break;
                            case 42:
                            case 46:
                                $data['skill'][23][] = $obj;
                                break;
                            case 47:
                                $data['skill'][24][] = $obj;
                                break;
                            case 49:
                                $data['skill'][25][] = $obj;
                                break;
                        }
                    }
                    $data['status'] = 1;
                    break;
                case 'p':
                case 'lp':
                    $skills = PSkill::where('fix_id','!=','0')->get();
                    foreach($skills as $skill){
                        $obj = new \stdClass;
                        if($type == 'lp')
                            $obj->units = $skill->minLinkUnits();
                        else
                            $obj->units = $skill->minUnits();
                        if(sizeof($obj->units) == 0){continue;}
                        $obj->name = $skill->name;
                        $obj->detail = $skill->detail;
                        $obj->detailCn = $skill->getDetailCn();

                        foreach ($obj->units as $unit) {
                            $unit->image = $imageUtil->getIconFlicker($function->getTriId($unit->draw_id));
                        }
                        if($skill->skill_trap_pass_active == 2)
                            $data['skill'][1][] = $obj;
                        elseif ($skill->skill_powup_kind_active == 2)
                            $data['skill'][2][] = $obj;
                        elseif ($skill->skill_counter_atk_active == 2)
                            $data['skill'][3][] = $obj;
                        elseif ($skill->skill_damage_recovery_active == 2 )
                            $data['skill'][4][] = $obj;
                        elseif ($skill->skill_hp_full_powup_active == 2 || $skill->skill_dying_powup_active == 2)
                            $data['skill'][5][] = $obj;
                        elseif ($skill->skill_backatk_pass_active == 2)
                            $data['skill'][6][] = $obj;
                        elseif ($skill->skill_decline_dmg_elem_active == 2 || $skill->skill_decline_dmg_kind_active == 2)
                            $data['skill'][7][] = $obj;
                        elseif ($skill->skill_boost_chance_active == 2)
                            $data['skill'][8][] = $obj;
                        else{
                            switch($skill->skill_type){
                                case 1:
                                    $data['skill'][9][] = $obj;
                                    break;
                                case 3:
                                    $data['skill'][10][] = $obj;
                                    break;
                                case 4:
                                    $data['skill'][11][] = $obj;
                                    break;
                                case 5:
                                    $data['skill'][12][] = $obj;
                                    break;
                                case 6:
                                    $data['skill'][13][] = $obj;
                                    break;
                                case 7:
                                    $data['skill'][14][] = $obj;
                                    break;
                                case 9:
                                    $data['skill'][15][] = $obj;
                                    break;
                                case 10:
                                    $data['skill'][16][] = $obj;
                                    break;
                                case 11:
                                    $data['skill'][17][] = $obj;
                                    break;
                                case 13:
                                    $data['skill'][18][] = $obj;
                                    break;
                                case 14:
                                    $data['skill'][19][] = $obj;
                                    break;
                                case 15:
                                    $data['skill'][20][] = $obj;
                                    break;
                            }
                        }
                    }
                    $data['status'] = 1;
                    break;
                default:
                    $data['status'] = 0;
                    break;
            }
            $data['type'] = $type;
            // cache
            $expiresAt = new Carbon('next friday');
            Cache::put($key, $data, $expiresAt);
        }
        return response()->json($data);
    }
}
