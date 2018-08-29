<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\GuerrillaBoss;
use App\Models\Unit;
use App\Models\Evo;
use App\Models\LinkSystem;
use App\Models\Quest;
use App\Models\QuestFloor;
use App\Models\Enemy;
use App\Models\Area;
use App\Models\AreaCategory;
use App\Models\ASkill;
use App\Models\LSkill;
use App\Models\NSkill;
use App\Models\PSkill;
use App\Models\UserRank;
use App\Models\Translate;
use App\Http\Requests;
use App\Util\FunctionUtil;
use App\Util\ImageUtil;
use \Cache;
use \DB;
use Carbon\Carbon;

class PagesController extends Controller
{
    public function test(){
        $data = array();
        $quests = Quest::where('fix_id','!=','0')->get();
        foreach($quests as $quest){
            $tran = Translate::where([
                ['type','=','1'],
                ['id','=',$quest->fix_id],
            ])->first();
            if($tran === null)
                $data['quest'][] = $quest;
        }
        $units = Unit::where('fix_id','!=','0')->orderBy('draw_id')->get();
        foreach($units as $unit){
            $tran = Translate::where([
                ['type','=','2'],
                ['id','=',$unit->draw_id],
            ])->first();
            if($tran === null)
                $data['unit'][] = $unit;
        }
        return view('test', $data);
    }

    public function index(){
        $function = new FunctionUtil;
        $imageUtil = new ImageUtil;
        $data = array();
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
        $data = compact('unitlist');

        $key = 'blade_event_egg';
        if (Cache::has($key)){
            $data['egg'] = Cache::get($key);
            $data['material'] = Cache::get('blade_event_material');
            $data['events'] = Cache::get('blade_event_schedule');
        } else {
            // Event
            $today = Carbon::now();
            $date = $today->format('Ymd');
            $events = Event::where('period_type','=',2)->get();
            $egg = array();
            $material = array();
            foreach($events as $event){
                $weekday = $function->getCycleDay($event->cycle_date_type);
                if(Carbon::now()->dayOfWeek == $weekday){   // egg
                    switch($event->event_id){
                        case 200100:
                            if(!isset($egg['fire']['area'])){
                                $egg['fire']['area'] = $event->area();
                                $egg['fire']['boss'] = $event->area()->getBoss();
                            }
                            $egg['fire'][] = Carbon::now()->hour($event->cycle_timing_start)->subHour()->format('H');
                            break;
                        case 200200:
                            if(!isset($egg['water']['area'])){
                                $egg['water']['area'] = $event->area();
                                $egg['water']['boss'] = $event->area()->getBoss();
                            }
                            $egg['water'][] = Carbon::now()->hour($event->cycle_timing_start)->subHour()->format('H');
                            break;
                        case 200300:
                            if(!isset($egg['wind']['area'])){
                                $egg['wind']['area'] = $event->area();
                                $egg['wind']['boss'] = $event->area()->getBoss();
                            }
                            $egg['wind'][] = Carbon::now()->hour($event->cycle_timing_start)->subHour()->format('H');
                            break;
                        case 200400:
                            if(!isset($egg['light']['area'])){
                                $egg['light']['area'] = $event->area();
                                $egg['light']['boss'] = $event->area()->getBoss();
                            }
                            $egg['light'][] = Carbon::now()->hour($event->cycle_timing_start)->subHour()->format('H');
                            break;
                        case 200500:
                            if(!isset($egg['dark']['area'])){
                                $egg['dark']['area'] = $event->area();
                                $egg['dark']['boss'] = $event->area()->getBoss();
                            }
                            $egg['dark'][] = Carbon::now()->hour($event->cycle_timing_start)->subHour()->format('H');
                            break;
                        case 200600:
                            if(!isset($egg['none']['area'])){
                                $egg['none']['area'] = $event->area();
                                $egg['none']['boss'] = $event->area()->getBoss();
                            }
                            $egg['none'][] = Carbon::now()->hour($event->cycle_timing_start)->subHour()->format('H');
                            break;
                        case 200700:
                            if(!isset($egg['mixed']['area'])){
                                $egg['mixed']['area'] = $event->area();
                                $egg['mixed']['boss'] = $event->area()->getBoss();
                            }
                            $egg['mixed'][] = Carbon::now()->hour($event->cycle_timing_start)->subHour()->format('H');
                            break;
                        case 200800:
                            if(!isset($egg['plus']['area'])){
                                $egg['plus']['area'] = $event->area();
                                $egg['plus']['boss'] = $event->area()->getBoss();
                            }
                            $egg['plus'][] = Carbon::now()->hour($event->cycle_timing_start)->subHour()->format('H');
                            break;
                    }
                } else if($event->cycle_date_type == 254) { //material
                    switch($event->event_id){
                        case 200900:
                        case 200901:
                        case 200902:
                        case 200903:
                        case 200904:
                        case 200905:
                        case 200906:
                        case 200907:
                        case 200908:
                            $material[$event->event_id][] = Carbon::now()->hour($event->cycle_timing_start)->subHour()->format('H');
                            break;
                    }
                }
            }
            $data['egg'] = $egg;
            $data['material'] = $material;

            $schedule = array();
            $events = Event::where('event.event_schedule_show','=','2')
                            ->where(function ($query) {
                                $date = Carbon::now()->format('Ymd');
                                $query->where([['event.timing_start','<=',$date.'00'],
                                                ['event.timing_end','>=',$date.'24']])
                                      ->orWhere('event.timing_start','>=',$date.'00');
                            })->get();
            foreach($events as $event){
                $area = $event->area();
                if($area != null){
                    if($event->timing_start > $date.'24')
                        $schedule['future'][] = [$event,$area,$area->getBoss()];
                    else
                        $schedule['today'][] = [$event,$area,$area->getBoss()];
                }
            }
            $data['events'] = $schedule;
            // cache
            $expiresAt = new Carbon('tomorrow');
            Cache::put('blade_event_egg', $egg, $expiresAt);
            Cache::put('blade_event_material', $material, $expiresAt);
            Cache::put('blade_event_schedule', $schedule, $expiresAt);
        }
        return view('min.welcome', $data);
    }

    public function unit($id){
        $key = 'blade_unit'.$id;
        //if (Cache::has($key)){
        //    $data = Cache::get($key);
        //} else {
            $function = new FunctionUtil;
            $unit = Unit::where('draw_id','=',$id)->firstOrFail();
            $data = compact('unit');

            $detailcn = $unit->getTranslate();
            if($detailcn !== null)
                $data['detailcn'] = $detailcn->text;

            if($unit->skill_leader != 0){
                $ls = $unit->getSkill('ls');
                $data['lsName'] = $ls->name;
                $data['lsDetail'] = $ls->detail;
                $data['lsDetailCn'] = $ls->getDetailCn();
            }
            if($unit->skill_limitbreak != 0){
                $as = $unit->getSkill('as');
                $data['asName'] = $as->name;
                $data['asDetail'] = $as->detail;
                $data['asDetailCn'] = $as->getDetailCn();
                $data['asMax'] = $as->use_turn;
                $data['asMin'] = $as->use_turn - $as->level_max;
                $data['sameAS'] = $as->units();
            }
            $ns1 = $unit->getSkill('ns1');
            $data['ns1Name'] = $ns1->name;
            $data['ns1Detail'] = $ns1->detail;
            $data['ns1DetailCn'] = $ns1->getDetailCn();
            $data['ns1Card'] = $ns1->getCard();
            if($unit->skill_active1 != 0){
                $ns2 = $unit->getSkill('ns2');
                $data['ns2Name'] = $ns2->name;
                $data['ns2Detail'] = $ns2->detail;
                $data['ns2DetailCn'] = $ns2->getDetailCn();
                $data['ns2Card'] = $ns2->getCard();
            }
            if($unit->skill_passive != 0){
                $ps = $unit->getSkill('ps');
                $data['psName'] = $ps->name;
                $data['psDetail'] = $ps->detail;
                $data['psDetailCn'] = $ps->getDetailCn();
            }
            if($unit->link_enable == 2){
                $link = LinkSystem::where('elem','=',$unit->element)->firstOrFail();
                $data['link'] = $link;
                $data['link_hp'] = $link->hp;
                $data['link_atk'] = $link->atk;

                $data['race_bouns'] = $function->linkRaceBonus($unit->rare, $unit->kind, $unit->sub_kind);

                if($unit->link_skill_active != 0){
                    $lns = $unit->getSkill('lns');
                    $data['lnsName'] = $lns->name;
                    $data['lnsDetail'] = $lns->detail;
                    $data['lnsDetailCn'] = $lns->getDetailCn();
                    $data['lnsOdds'] = $lns->skill_link_odds;
                }
                if($unit->link_skill_passive != 0){
                    $lps = $unit->getSkill('lps');
                    $data['lpsName'] = $lps->name;
                    $data['lpsDetail'] = $lps->detail;
                    $data['lpsDetailCn'] = $lps->getDetailCn();
                }

                $data['linkPart'][0] = $unit->linkPart1();
                $data['linkPart'][1] = $unit->linkPart2();
                $data['linkPart'][2] = $unit->linkPart3();
                $data['delLinkPart'][0] = $unit->delLinkPart1();
                $data['delLinkPart'][1] = $unit->delLinkPart2();
                $data['delLinkPart'][2] = $unit->delLinkPart3();
            }
            $limit_over = $unit->limitOver();
            if($limit_over != null){
                $data['limit_over_max'] = $limit_over->limit_over_max;
                $data['limit_over_max_hp'] = $limit_over->limit_over_max_hp;
                $data['limit_over_max_atk'] = $limit_over->limit_over_max_atk;
                $data['limit_over_max_cost'] = $limit_over->limit_over_max_cost;
                $data['limit_over_max_charm'] = $limit_over->limit_over_max_charm;
                $data['limit_grow'] = $limit_over->limit_grow;
            } else {
                $data['limit_over_max'] = 0;
                $data['limit_over_max_hp'] = 0;
                $data['limit_over_max_atk'] =0;
                $data['limit_over_max_cost'] = 0;
                $data['limit_over_max_charm'] = 0;
                $data['limit_grow'] = 0;
            }
            // arrays
            $data['evos'] = Evo::where('unit_id_parts1','=',$unit->fix_id)->orWhere('unit_id_parts2','=',$unit->fix_id)->orWhere('unit_id_parts3','=',$unit->fix_id)->orWhere('unit_id_parts4','=',$unit->fix_id)->get();
            $data['areas'] = $function->getArea($unit);
            $data['evoFrom'] = Evo::where('unit_id_after','=',$unit->fix_id)->first();
            $data['evoTo'] = Evo::where('unit_id_pre','=',$unit->fix_id)->first();

            // cache
            $expiresAt = new Carbon('next friday');
            Cache::put($key, $data, $expiresAt);
        //}
        return view('min.unit', $data);
    }

    public function quest($id){
        $key = 'blade_quest'.$id;
        if (Cache::has($key)){
            $data = Cache::get($key);
        } else {
            $quest = Quest::where('fix_id','=',$id)->firstOrFail();
            $data = compact('quest');

            $area = $quest->area();
            $data['area_cate_name'] = $area->areaCate()->area_cate_name;
            $data['area_name'] = $area->area_name;
            $data['area_id'] = $area->fix_id;
            $areaKey = $area->areaKey();
            if(!is_null($areaKey))
                $data['area_key'] = $areaKey->key_name;

            $storycn = $quest->getTranslate();
            if($storycn !== null)
                $data['storycn'] = $storycn->text;

            // quest requirement
            if($quest->quest_requirement_id != 0){
                $quest_requirement = $quest->questRequirement();
                if($quest->battle_chain == 2)
                    $data['quest_requirement'][] = '連鎖戰鬥: 有';
                if($quest->enable_continue == 1)
                    $data['quest_requirement'][] = '不可使用硬幣復活';
                // elem
                if($quest_requirement->elem_fire == 2 || $quest_requirement->elem_water == 2 || $quest_requirement->elem_wind == 2 || $quest_requirement->elem_light == 2 || $quest_requirement->elem_dark == 2 || $quest_requirement->elem_naught == 2){
                    $first = true;
                    $quest_requirement_elem = '禁止使用';
                    if($quest_requirement->elem_fire == 2){
                        if($first){$first = false;}else{$quest_requirement_elem .= '、';}
                        $quest_requirement_elem .= '火';
                    }
                    if($quest_requirement->elem_water == 2){
                        if($first){$first = false;}else{$quest_requirement_elem .= '、';}
                        $quest_requirement_elem .= '水';
                    }
                    if($quest_requirement->elem_wind == 2){
                        if($first){$first = false;}else{$quest_requirement_elem .= '、';}
                        $quest_requirement_elem .= '風';
                    }
                    if($quest_requirement->elem_light == 2){
                        if($first){$first = false;}else{$quest_requirement_elem .= '、';}
                        $quest_requirement_elem .= '光';
                    }
                    if($quest_requirement->elem_dark == 2){
                        if($first){$first = false;}else{$quest_requirement_elem .= '、';}
                        $quest_requirement_elem .= '暗';
                    }
                    if($quest_requirement->elem_naught == 2){
                        if($first){$first = false;}else{$quest_requirement_elem .= '、';}
                        $quest_requirement_elem .= '無';
                    }
                    $quest_requirement_elem .= '屬性以外的Unit';
                    if($quest_requirement->num_elem == 1)
                        $quest_requirement_elem .= '(不能有副屬性)';
                    $data['quest_requirement'][] = $quest_requirement_elem;
                }
                // kind
                if($quest_requirement->kind_human == 2 || $quest_requirement->kind_fairy == 2 || $quest_requirement->kind_demon == 2 || $quest_requirement->kind_dragon == 2 || $quest_requirement->kind_machine == 2 || $quest_requirement->kind_beast == 2 || $quest_requirement->kind_god == 2 || $quest_requirement->kind_egg == 2){
                    $first = true;
                    $quest_requirement_kind = '禁止使用';
                    if($quest_requirement->kind_human == 2){
                        if($first){$first = false;}else{$quest_requirement_kind .= '、';}
                        $quest_requirement_kind .= '人類';
                    }
                    if($quest_requirement->kind_fairy == 2){
                        if($first){$first = false;}else{$quest_requirement_kind .= '、';}
                        $quest_requirement_kind .= '妖精類';
                    }
                    if($quest_requirement->kind_demon == 2){
                        if($first){$first = false;}else{$quest_requirement_kind .= '、';}
                        $quest_requirement_kind .= '魔物類';
                    }
                    if($quest_requirement->kind_dragon == 2){
                        if($first){$first = false;}else{$quest_requirement_kind .= '、';}
                        $quest_requirement_kind .= '龍類';
                    }
                    if($quest_requirement->kind_machine == 2){
                        if($first){$first = false;}else{$quest_requirement_kind .= '、';}
                        $quest_requirement_kind .= '機械類';
                    }
                    if($quest_requirement->kind_beast == 2){
                        if($first){$first = false;}else{$quest_requirement_kind .= '、';}
                        $quest_requirement_kind .= '獸類';
                    }
                    if($quest_requirement->kind_god == 2){
                        if($first){$first = false;}else{$quest_requirement_kind .= '、';}
                        $quest_requirement_kind .= '神類';
                    }
                    if($quest_requirement->kind_egg == 2){
                        if($first){$first = false;}else{$quest_requirement_kind .= '、';}
                        $quest_requirement_kind .= '強化合成類';
                    }
                    $quest_requirement_kind .= '以外的Unit';
                    if($quest_requirement->num_kind == 1)
                        $quest_requirement_kind .= '(不能有副種族)';
                    $data['quest_requirement'][] = $quest_requirement_kind;
                }
                if($quest_requirement->num_unit != 0)
                    $data['quest_requirement'][] = '只能攜帶'.$quest_requirement->num_unit.'隻Unit闖關';
                if($quest_requirement->much_name == 2)
                    $data['quest_requirement'][] = '禁止使用同名Unit';
                if($quest_requirement->require_unit_00 != 0){
                    for($i = 0; $i < 5; $i++){
                        $data['quest_requirement']['require_unit'][] = $quest_requirement->require_unit($i);
                    }
                }
                if($quest_requirement->limit_cost != 0)
                    $data['quest_requirement'][] = '禁止使用'.($quest_requirement->limit_cost+1).' COST或以上的Unit';
                if($quest_requirement->limit_cost_total != 0)
                    $data['quest_requirement'][] = '隊伍COST禁止超越'.$quest_requirement->limit_cost_total;
                if($quest_requirement->limit_unit_lv != 0)
                    $data['quest_requirement'][] = '禁止使用Lv.'.$quest_requirement->limit_unit_lv.'或以上的Unit';
                if($quest_requirement->limit_unit_lv_total != 0)
                    $data['quest_requirement'][] = '隊伍Lv.禁止超越'.$quest_requirement->limit_unit_lv_total;
                if($quest_requirement->limit_rank != 0)
                    $data['quest_requirement'][] = 'RANK'.($quest_requirement->limit_rank-1).'以下禁止入場';
                //rule_disable_as, rule_disable_ls, rule_heal_half, rule_disable_affinity
                if($quest_requirement->fix_unit_00_enable == 2){
                    $data['quest_requirement']['fix_team']['fix_unit'][0]['unit'] = $quest_requirement->fix_unit(0);
                    $data['quest_requirement']['fix_team']['fix_unit'][0]['lv'] = $quest_requirement->fix_unit_00_lv;
                    $data['quest_requirement']['fix_team']['fix_unit'][0]['lbs_lv'] = $quest_requirement->fix_unit_00_lbs_lv; // limit break skill lv
                    $data['quest_requirement']['fix_team']['fix_unit'][0]['plus_hp'] = $quest_requirement->fix_unit_00_plus_hp;
                    $data['quest_requirement']['fix_team']['fix_unit'][0]['plus_atk'] = $quest_requirement->fix_unit_00_plus_atk;
                    if($quest_requirement->fix_unit_00_link_id != 0){
                        $data['quest_requirement']['fix_team']['link_unit'][0]['unit'] = $quest_requirement->link_unit(0);
                        $data['quest_requirement']['fix_team']['link_unit'][0]['lv'] = $quest_requirement->fix_unit_00_link_lv;
                        $data['quest_requirement']['fix_team']['link_unit'][0]['plus_hp'] = $quest_requirement->fix_unit_00_link_plus_hp;
                        $data['quest_requirement']['fix_team']['link_unit'][0]['plus_atk'] = $quest_requirement->fix_unit_00_link_plus_atk;
                        $data['quest_requirement']['fix_team']['link_unit'][0]['link_point'] = $quest_requirement->fix_unit_00_link_lbs_lv;
                    }
                }
                if($quest_requirement->fix_unit_01_enable == 2){
                    $data['quest_requirement']['fix_team']['fix_unit'][1]['unit'] = $quest_requirement->fix_unit(1);
                    $data['quest_requirement']['fix_team']['fix_unit'][1]['lv'] = $quest_requirement->fix_unit_01_lv;
                    $data['quest_requirement']['fix_team']['fix_unit'][1]['lbs_lv'] = $quest_requirement->fix_unit_01_lbs_lv; // limit break skill lv
                    $data['quest_requirement']['fix_team']['fix_unit'][1]['plus_hp'] = $quest_requirement->fix_unit_01_plus_hp;
                    $data['quest_requirement']['fix_team']['fix_unit'][1]['plus_atk'] = $quest_requirement->fix_unit_01_plus_atk;
                    if($quest_requirement->fix_unit_01_link_id != 0){
                        $data['quest_requirement']['fix_team']['link_unit'][1]['unit'] = $quest_requirement->link_unit(1);
                        $data['quest_requirement']['fix_team']['link_unit'][1]['lv'] = $quest_requirement->fix_unit_01_link_lv;
                        $data['quest_requirement']['fix_team']['link_unit'][1]['plus_hp'] = $quest_requirement->fix_unit_01_link_plus_hp;
                        $data['quest_requirement']['fix_team']['link_unit'][1]['plus_atk'] = $quest_requirement->fix_unit_01_link_plus_atk;
                        $data['quest_requirement']['fix_team']['link_unit'][1]['link_point'] = $quest_requirement->fix_unit_01_link_lbs_lv;
                    }
                }
                if($quest_requirement->fix_unit_02_enable == 2){
                    $data['quest_requirement']['fix_team']['fix_unit'][2]['unit'] = $quest_requirement->fix_unit(2);
                    $data['quest_requirement']['fix_team']['fix_unit'][2]['lv'] = $quest_requirement->fix_unit_02_lv;
                    $data['quest_requirement']['fix_team']['fix_unit'][2]['lbs_lv'] = $quest_requirement->fix_unit_02_lbs_lv; // limit break skill lv
                    $data['quest_requirement']['fix_team']['fix_unit'][2]['plus_hp'] = $quest_requirement->fix_unit_02_plus_hp;
                    $data['quest_requirement']['fix_team']['fix_unit'][2]['plus_atk'] = $quest_requirement->fix_unit_02_plus_atk;
                    if($quest_requirement->fix_unit_02_link_id != 0){
                        $data['quest_requirement']['fix_team']['link_unit'][2]['unit'] = $quest_requirement->link_unit(2);
                        $data['quest_requirement']['fix_team']['link_unit'][2]['lv'] = $quest_requirement->fix_unit_02_link_lv;
                        $data['quest_requirement']['fix_team']['link_unit'][2]['plus_hp'] = $quest_requirement->fix_unit_02_link_plus_hp;
                        $data['quest_requirement']['fix_team']['link_unit'][2]['plus_atk'] = $quest_requirement->fix_unit_02_link_plus_atk;
                        $data['quest_requirement']['fix_team']['link_unit'][2]['link_point'] = $quest_requirement->fix_unit_02_link_lbs_lv;
                    }
                }
                if($quest_requirement->fix_unit_03_enable == 2){
                    $data['quest_requirement']['fix_team']['fix_unit'][3]['unit'] = $quest_requirement->fix_unit(0);
                    $data['quest_requirement']['fix_team']['fix_unit'][3]['lv'] = $quest_requirement->fix_unit_03_lv;
                    $data['quest_requirement']['fix_team']['fix_unit'][3]['lbs_lv'] = $quest_requirement->fix_unit_03_lbs_lv; // limit break skill lv
                    $data['quest_requirement']['fix_team']['fix_unit'][3]['plus_hp'] = $quest_requirement->fix_unit_03_plus_hp;
                    $data['quest_requirement']['fix_team']['fix_unit'][3]['plus_atk'] = $quest_requirement->fix_unit_03_plus_atk;
                    if($quest_requirement->fix_unit_03_link_id != 0){
                        $data['quest_requirement']['fix_team']['link_unit'][3]['unit'] = $quest_requirement->link_unit(3);
                        $data['quest_requirement']['fix_team']['link_unit'][3]['lv'] = $quest_requirement->fix_unit_03_link_lv;
                        $data['quest_requirement']['fix_team']['link_unit'][3]['plus_hp'] = $quest_requirement->fix_unit_03_link_plus_hp;
                        $data['quest_requirement']['fix_team']['link_unit'][3]['plus_atk'] = $quest_requirement->fix_unit_03_link_plus_atk;
                        $data['quest_requirement']['fix_team']['link_unit'][3]['link_point'] = $quest_requirement->fix_unit_03_link_lbs_lv;
                    }
                }
                if($quest_requirement->fix_unit_04_enable == 2){
                    $data['quest_requirement']['fix_team']['fix_unit'][4]['unit'] = $quest_requirement->fix_unit(4);
                    $data['quest_requirement']['fix_team']['fix_unit'][4]['lv'] = $quest_requirement->fix_unit_04_lv;
                    $data['quest_requirement']['fix_team']['fix_unit'][4]['lbs_lv'] = $quest_requirement->fix_unit_04_lbs_lv; // limit break skill lv
                    $data['quest_requirement']['fix_team']['fix_unit'][4]['plus_hp'] = $quest_requirement->fix_unit_04_plus_hp;
                    $data['quest_requirement']['fix_team']['fix_unit'][4]['plus_atk'] = $quest_requirement->fix_unit_04_plus_atk;
                    if($quest_requirement->fix_unit_04_link_id != 0){
                        $data['quest_requirement']['fix_team']['link_unit'][4]['unit'] = $quest_requirement->link_unit(4);
                        $data['quest_requirement']['fix_team']['link_unit'][4]['lv'] = $quest_requirement->fix_unit_04_link_lv;
                        $data['quest_requirement']['fix_team']['link_unit'][4]['plus_hp'] = $quest_requirement->fix_unit_04_link_plus_hp;
                        $data['quest_requirement']['fix_team']['link_unit'][4]['plus_atk'] = $quest_requirement->fix_unit_04_link_plus_atk;
                        $data['quest_requirement']['fix_team']['link_unit'][4]['link_point'] = $quest_requirement->fix_unit_04_link_lbs_lv;
                    }
                }
            }
            // 1st time clear unit
            if($quest->clear_unit != 0){
                $data['quest_requirement']['clear_unit']['unit'] = $quest->clearUnit();
                $data['quest_requirement']['clear_unit']['lv'] = $quest->clear_unit_lv;
            }

            // build enemyArray
            $bossArray = array();
            $enemyArray = array();
            $questfloors = QuestFloor::where('quest_id','=',$id)->get();
            if(sizeof($questfloors) === 0){
                $data['noData'] = true;
                $data['boss'] = $quest->boss();
                for($i = 1; $i < 5; $i++){
                    if($quest->bossAbility($i)->fix_id != 0)
                        $data['abilities'][] = $quest->bossAbility($i);
                }
            }else{
                foreach($questfloors as $questfloor){
                    if($questfloor->boss_group_id != 0){
                        $bossGroup = $questfloor->bossGroup();
                        if($bossGroup != null){
            			    if ($bossGroup->enemy_id_1 != 0){$bossArray[] = $bossGroup->enemy_id_1; }
                            if ($bossGroup->enemy_id_2 != 0){$bossArray[] = $bossGroup->enemy_id_2; }
                            if ($bossGroup->enemy_id_3 != 0){$bossArray[] = $bossGroup->enemy_id_3; }
                            if ($bossGroup->enemy_id_4 != 0){$bossArray[] = $bossGroup->enemy_id_4; }
                            if ($bossGroup->enemy_id_5 != 0){$bossArray[] = $bossGroup->enemy_id_5; }
                            if ($bossGroup->enemy_id_6 != 0){$bossArray[] = $bossGroup->enemy_id_6; }
                            if ($bossGroup->enemy_id_7 != 0){$bossArray[] = $bossGroup->enemy_id_7; }

                            while($bossGroup->chain_id > 0){
                                $bossGroup = $bossGroup->chain();
                			    if ($bossGroup->enemy_id_1 != 0){$bossArray[] = $bossGroup->enemy_id_1; }
                                if ($bossGroup->enemy_id_2 != 0){$bossArray[] = $bossGroup->enemy_id_2; }
                                if ($bossGroup->enemy_id_3 != 0){$bossArray[] = $bossGroup->enemy_id_3; }
                                if ($bossGroup->enemy_id_4 != 0){$bossArray[] = $bossGroup->enemy_id_4; }
                                if ($bossGroup->enemy_id_5 != 0){$bossArray[] = $bossGroup->enemy_id_5; }
                                if ($bossGroup->enemy_id_6 != 0){$bossArray[] = $bossGroup->enemy_id_6; }
                                if ($bossGroup->enemy_id_7 != 0){$bossArray[] = $bossGroup->enemy_id_7; }
                            }
                        }
                    }
                    for($i = 1; $i<8 ;$i++){
                        $enemygroup = $questfloor->enemygroup($i);
                        if($enemygroup != null){
            			    if ($enemygroup->enemy_id_1 != 0){$enemyArray[] = $enemygroup->enemy_id_1; }
                            if ($enemygroup->enemy_id_2 != 0){$enemyArray[] = $enemygroup->enemy_id_2; }
                            if ($enemygroup->enemy_id_3 != 0){$enemyArray[] = $enemygroup->enemy_id_3; }
                            if ($enemygroup->enemy_id_4 != 0){$enemyArray[] = $enemygroup->enemy_id_4; }
                            if ($enemygroup->enemy_id_5 != 0){$enemyArray[] = $enemygroup->enemy_id_5; }
                            if ($enemygroup->enemy_id_6 != 0){$enemyArray[] = $enemygroup->enemy_id_6; }
                            if ($enemygroup->enemy_id_7 != 0){$enemyArray[] = $enemygroup->enemy_id_7; }
                        }
                    }
                }

                // boss
        		$bossArray = array_unique($bossArray);
        		$bossArray = array_values($bossArray);
                foreach($bossArray as $enemyId){
                    $temp = array();
                    $enemy = Enemy::where('fix_id','=',$enemyId)->first();
                    $temp['unit'] = $enemy->unit();
                    $temp['hp'] = $enemy->status_hp;
                    $temp['atk'] = $enemy->status_pow;
                    $temp['def'] = $enemy->status_def;
                    $temp['cd'] = $enemy->status_turn;
                    $temp['drop'] = $enemy->dropUnit();
                    if($enemy->ability1 != 0){
                        for ($j = 1; $j<5; $j++){
                            $ability = $enemy->ability($j);
                            if($ability->fix_id != 0)
                                $temp['ability'][] = $ability;
                        }
                    }
                    if($enemy->act_first != 0){
                        $temp['act_first'] = $enemy->actFirst()->getDetail($enemy->status_pow);
                    }
                    if($enemy->act_dead != 0){
                        $temp['act_dead'] = $enemy->actDead()->getDetail($enemy->status_pow);
                    }
                    if($enemy->act_table1 != 0){
                        for($i = 1; $i < 9; $i++){
                            $actTable = $enemy->actTable($i);
                            $temp2 = array();
                            for($j = 1; $j < 9; $j++){
                                $actionParam = $actTable->actionParam($j)->getDetail($enemy->status_pow);
                                if(isset($actionParam['detail'])){
                                    $temp2[] = $actionParam;
                                }
                            }
                            if(sizeof($temp2) > 0){
                                $temp['act_table'.$i]['timing_type'] = $actTable->timing_type;
                                $temp['act_table'.$i]['timing_param1'] = $actTable->timing_param1;
                                $temp['act_table'.$i]['action_type'] = $actTable->action_select_type;
                                $temp['act_table'.$i]['moves'] = $temp2;
                            }
                        }
                    }
                    $data['boss'][] = $temp;
                }

                // 小怪
        		$enemyArray = array_unique($enemyArray);
        		$enemyArray = array_values($enemyArray);
                foreach($enemyArray as $enemyId){
                    $temp = array();
                    $enemy = Enemy::where('fix_id','=',$enemyId)->first();
                    $temp['unit'] = $enemy->unit();
                    $temp['hp'] = $enemy->status_hp;
                    $temp['atk'] = $enemy->status_pow;
                    $temp['def'] = $enemy->status_def;
                    $temp['cd'] = $enemy->status_turn;
                    $temp['drop'] = $enemy->dropUnit();
                    if($enemy->act_first != 0){
                        $temp['act_first'] = $enemy->actFirst()->get()->getDetail($enemy->status_pow);
                    }
                    if($enemy->act_dead != 0){
                        $temp['act_dead'] = $enemy->actDead()->get()->getDetail($enemy->status_pow);
                    }
                    if($enemy->act_table1 != 0){
                        for($i = 1; $i < 9; $i++){
                            $actTable = $enemy->actTable($i);
                            $temp2 = array();
                            for($j = 1; $j < 9; $j++){
                                $actionParam = $actTable->actionParam($j)->getDetail($enemy->status_pow);
                                if(isset($actionParam['detail'])){
                                    $temp2[] = $actionParam;
                                }
                            }
                            if(sizeof($temp2) > 0){
                                $temp['act_table'.$i]['timing_type'] = $actTable->timing_type;
                                $temp['act_table'.$i]['timing_param1'] = $actTable->timing_param1;
                                $temp['act_table'.$i]['action_type'] = $actTable->action_select_type;
                                $temp['act_table'.$i]['moves'] = $temp2;
                            }
                        }
                    }
                    $data['enemy'][] = $temp;
                }

                // 格子分佈
                foreach($questfloors as $questfloor){
                    $floor = array();
                    for($i = 1; $i<8 ;$i++){
                        // enemy
                        $enemygroup = $questfloor->enemygroup($i);
                        if($enemygroup != null){
            			    if ($enemygroup->enemy_id_1 != 0){ $floor[$i]['enemy'][] = $enemygroup->enemy(1)->unit(); }
                            if ($enemygroup->enemy_id_2 != 0){ $floor[$i]['enemy'][] = $enemygroup->enemy(2)->unit(); }
                            if ($enemygroup->enemy_id_3 != 0){ $floor[$i]['enemy'][] = $enemygroup->enemy(3)->unit(); }
                            if ($enemygroup->enemy_id_4 != 0){ $floor[$i]['enemy'][] = $enemygroup->enemy(4)->unit(); }
                            if ($enemygroup->enemy_id_5 != 0){ $floor[$i]['enemy'][] = $enemygroup->enemy(5)->unit(); }
                            if ($enemygroup->enemy_id_6 != 0){ $floor[$i]['enemy'][] = $enemygroup->enemy(6)->unit(); }
                            if ($enemygroup->enemy_id_7 != 0){ $floor[$i]['enemy'][] = $enemygroup->enemy(7)->unit(); }
                        }
                        // trap
                        $trapgroup = $questfloor->trapgroup($i);
                        for($j = 1; $j<8 ;$j++){
                            if($trapgroup != null)
                                $floor[$i]['trap'][] = $trapgroup->panel($j);
                        }
                        // money
                        $itemgroup = $questfloor->itemgroup($i);
                        for($j = 1; $j<8 ;$j++){
                            $tempArr[] = $itemgroup->panel($j);
                        }
                        $floor[$i]['money']['min'] = min($tempArr);
                        $floor[$i]['money']['max'] = max($tempArr);
                        $floor[$i]['money']['icon'] = $quest->area()->res_icon_box;
                        // heal
                        /*$healgroup = $questfloor->healgroup($i);
                        for($j = 1; $j<8 ;$j++){
                            $floor[$i]['heal'][] = $healgroup->panel($j);
                        }*/
                    }
                    $data['floors'][] = $floor;
                }
            }
            // cache
            $expiresAt = new Carbon('next friday');
            Cache::put($key, $data, $expiresAt);
        }
        return view('min.quest', $data);
    }

    public function area($id){
        $key = 'blade_area'.$id;
        if (Cache::has($key)){
            $data = Cache::get($key);
        } else {
            $area = Area::where('fix_id','=',$id)->firstOrFail();
            $data = compact('area');
            $data['area_cate_name'] = $area->areaCate()->area_cate_name;
            $data['quests'] = Quest::where('area_id','=',$id)->get();
            // cache
            $expiresAt = new Carbon('next friday');
            Cache::put($key, $data, $expiresAt);
        }
        return view('min.area', $data);
    }

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
        $data = compact('unitlist');
        return view('min.unitlist', $data);
    }

    public function questlist(){
        $key = 'blade_questlist';
        if (Cache::has($key)){
            $data = Cache::get($key);
        } else {
            $data = array();
            $areaCate = AreaCategory::where('fix_id','!=','0')->orderBy('area_cate_type', 'asc')->orderBy('questlist_sort', 'asc')->get();
            foreach($areaCate as $cate){
                $data['areaCate'][$cate->area_cate_type][] = $cate;
                foreach($cate->getAreas() as $area){
                    $data['areaById'][$cate->fix_id][] = [$area,$area->getBoss()];
                }
            }
            // cache
            $expiresAt = new Carbon('next friday');
            Cache::put($key, $data, $expiresAt);
        }
        return view('min.questlist', $data);
    }

    public function skill($type){
        $data = array('type' => $type);
        return view('min.skill', $data);
    }

    public function voteResult(){
        $data = array();
        /*array('rank' => '1', 'unit' => Unit::where('draw_id','=','')->first(), 'type' => '', 'change' => ''),
        array('rank' => '2', 'unit' => Unit::where('draw_id','=','')->first(), 'type' => '', 'change' => ''),
        array('rank' => '3', 'unit' => Unit::where('draw_id','=','')->first(), 'type' => '', 'change' => ''),
        array('rank' => '4', 'unit' => Unit::where('draw_id','=','')->first(), 'type' => '', 'change' => ''),
        array('rank' => '5', 'unit' => Unit::where('draw_id','=','')->first(), 'type' => '', 'change' => ''),
        array('rank' => '6', 'unit' => Unit::where('draw_id','=','')->first(), 'type' => '', 'change' => ''),
        array('rank' => '7', 'unit' => Unit::where('draw_id','=','')->first(), 'type' => '', 'change' => ''),
        array('rank' => '8', 'unit' => Unit::where('draw_id','=','')->first(), 'type' => '', 'change' => ''),
        array('rank' => '9', 'unit' => Unit::where('draw_id','=','')->first(), 'type' => '', 'change' => ''),
        array('rank' => '0', 'unit' => Unit::where('draw_id','=','')->first(), 'type' => '', 'change' => ''),*/
        $key = 'blade_vote_result_1';
        if (Cache::has($key)){
            $data['vote1'] = Cache::get($key);
        } else {
            $vote = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','148')->first(), 'type' => '', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','146')->first(), 'type' => '', 'change' => ''),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','150')->first(), 'type' => '', 'change' => ''),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','296')->first(), 'type' => '', 'change' => ''),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','12')->first(), 'type' => '', 'change' => ''),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','262')->first(), 'type' => '', 'change' => ''),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','8')->first(), 'type' => '', 'change' => ''),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','20')->first(), 'type' => '', 'change' => ''),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','264')->first(), 'type' => '', 'change' => ''),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','260')->first(), 'type' => '', 'change' => ''),
                array('rank' => '11', 'unit' => Unit::where('draw_id','=','16')->first(), 'type' => '', 'change' => ''),
                array('rank' => '12', 'unit' => Unit::where('draw_id','=','4')->first(), 'type' => '', 'change' => ''),
                array('rank' => '13', 'unit' => Unit::where('draw_id','=','343')->first(), 'type' => '', 'change' => ''),
                array('rank' => '14', 'unit' => Unit::where('draw_id','=','156')->first(), 'type' => '', 'change' => ''),
                array('rank' => '15', 'unit' => Unit::where('draw_id','=','154')->first(), 'type' => '', 'change' => ''),
                array('rank' => '16', 'unit' => Unit::where('draw_id','=','152')->first(), 'type' => '', 'change' => ''),
                array('rank' => '17', 'unit' => Unit::where('draw_id','=','274')->first(), 'type' => '', 'change' => ''),
                array('rank' => '18', 'unit' => Unit::where('draw_id','=','286')->first(), 'type' => '', 'change' => ''),
                array('rank' => '19', 'unit' => Unit::where('draw_id','=','268')->first(), 'type' => '', 'change' => ''),
                array('rank' => '20', 'unit' => Unit::where('draw_id','=','220')->first(), 'type' => '', 'change' => ''),
            );
            $data['vote1'] = $vote;
            // cache
            Cache::forever($key, $vote);
        }
        $key = 'blade_vote_result_2';
        if (Cache::has($key)){
            $data['vote2'] = Cache::get($key);
        } else {
            $vote = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','296')->first(), 'type' => 'up', 'change' => '3'),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','148')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','146')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','152')->first(), 'type' => 'up', 'change' => '12'),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','274')->first(), 'type' => 'up', 'change' => '12'),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','428')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','436')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','150')->first(), 'type' => 'down', 'change' => '5'),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','8')->first(), 'type' => 'down', 'change' => '2'),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','262')->first(), 'type' => 'down', 'change' => '3'),
                array('rank' => '11', 'unit' => Unit::where('draw_id','=','474')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '12', 'unit' => Unit::where('draw_id','=','218')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '13', 'unit' => Unit::where('draw_id','=','264')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '14', 'unit' => Unit::where('draw_id','=','260')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '15', 'unit' => Unit::where('draw_id','=','482')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '16', 'unit' => Unit::where('draw_id','=','20')->first(), 'type' => 'down', 'change' => '8'),
                array('rank' => '17', 'unit' => Unit::where('draw_id','=','434')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '18', 'unit' => Unit::where('draw_id','=','343')->first(), 'type' => 'down', 'change' => '5'),
                array('rank' => '19', 'unit' => Unit::where('draw_id','=','534')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '20', 'unit' => Unit::where('draw_id','=','426')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '21', 'unit' => Unit::where('draw_id','=','12')->first(), 'type' => 'down', 'change' => '16'),
                array('rank' => '22', 'unit' => Unit::where('draw_id','=','355')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '23', 'unit' => Unit::where('draw_id','=','424')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '24', 'unit' => Unit::where('draw_id','=','154')->first(), 'type' => 'down', 'change' => '9'),
                array('rank' => '25', 'unit' => Unit::where('draw_id','=','16')->first(), 'type' => 'down', 'change' => '14'),
                array('rank' => '26', 'unit' => Unit::where('draw_id','=','156')->first(), 'type' => 'down', 'change' => '12'),
                array('rank' => '27', 'unit' => Unit::where('draw_id','=','286')->first(), 'type' => 'down', 'change' => '9'),
                array('rank' => '28', 'unit' => Unit::where('draw_id','=','4')->first(), 'type' => 'down', 'change' => '16'),
                array('rank' => '29', 'unit' => Unit::where('draw_id','=','333')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '30', 'unit' => Unit::where('draw_id','=','432')->first(), 'type' => 'new', 'change' => ''),
            );
            $data['vote2'] = $vote;
            // cache
            Cache::forever($key, $vote);
        }
        $key = 'blade_vote_result_3';
        if (Cache::has($key)){
            $data['vote3'] = Cache::get($key);
        } else {
            $vote = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','650')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','296')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','148')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','610')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','146')->first(), 'type' => 'down', 'change' => '2'),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','559')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','595')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','436')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','8')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','482')->first(), 'type' => 'up', 'change' => '5'),
                array('rank' => '11', 'unit' => Unit::where('draw_id','=','682')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '12', 'unit' => Unit::where('draw_id','=','474')->first(), 'type' => 'down', 'change' => '3'),
                array('rank' => '13', 'unit' => Unit::where('draw_id','=','20')->first(), 'type' => 'up', 'change' => '3'),
                array('rank' => '14', 'unit' => Unit::where('draw_id','=','636')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '15', 'unit' => Unit::where('draw_id','=','152')->first(), 'type' => 'down', 'change' => '11'),
                array('rank' => '16', 'unit' => Unit::where('draw_id','=','12')->first(), 'type' => 'up', 'change' => '5'),
                array('rank' => '17', 'unit' => Unit::where('draw_id','=','150')->first(), 'type' => 'down', 'change' => '9'),
                array('rank' => '18', 'unit' => Unit::where('draw_id','=','262')->first(), 'type' => 'down', 'change' => '9'),
                array('rank' => '19', 'unit' => Unit::where('draw_id','=','274')->first(), 'type' => 'down', 'change' => '14'),
                array('rank' => '20', 'unit' => Unit::where('draw_id','=','678')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '21', 'unit' => Unit::where('draw_id','=','428')->first(), 'type' => 'down', 'change' => '15'),
                array('rank' => '22', 'unit' => Unit::where('draw_id','=','218')->first(), 'type' => 'down', 'change' => '10'),
                array('rank' => '23', 'unit' => Unit::where('draw_id','=','264')->first(), 'type' => 'down', 'change' => '9'),
                array('rank' => '24', 'unit' => Unit::where('draw_id','=','343')->first(), 'type' => 'down', 'change' => '6'),
                array('rank' => '25', 'unit' => Unit::where('draw_id','=','336')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '26', 'unit' => Unit::where('draw_id','=','648')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '27', 'unit' => Unit::where('draw_id','=','4')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '28', 'unit' => Unit::where('draw_id','=','286')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '29', 'unit' => Unit::where('draw_id','=','278')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '30', 'unit' => Unit::where('draw_id','=','426')->first(), 'type' => 'down', 'change' => '10'),
                array('rank' => '31', 'unit' => Unit::where('draw_id','=','355')->first(), 'type' => 'down', 'change' => '8'),
                array('rank' => '32', 'unit' => Unit::where('draw_id','=','154')->first(), 'type' => 'down', 'change' => '8'),
                array('rank' => '33', 'unit' => Unit::where('draw_id','=','260')->first(), 'type' => 'down', 'change' => '19'),
                array('rank' => '34', 'unit' => Unit::where('draw_id','=','347')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '35', 'unit' => Unit::where('draw_id','=','351')->first(), 'type' => 'down', 'change' => '11'),
                array('rank' => '36', 'unit' => Unit::where('draw_id','=','16')->first(), 'type' => 'down', 'change' => '9'),
                array('rank' => '37', 'unit' => Unit::where('draw_id','=','156')->first(), 'type' => 'down', 'change' => '9'),
                array('rank' => '38', 'unit' => Unit::where('draw_id','=','333')->first(), 'type' => 'down', 'change' => '22'),
                array('rank' => '39', 'unit' => Unit::where('draw_id','=','434')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '40', 'unit' => Unit::where('draw_id','=','630')->first(), 'type' => 'new', 'change' => ''),
            );
            $data['vote3'] = $vote;
            // cache
            Cache::forever($key, $vote);
        }
        $key = 'blade_vote_result_4';
        if (Cache::has($key)){
            $data['vote4'] = Cache::get($key);
        } else {
            $vote = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','650')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','750')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','296')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','610')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','559')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','148')->first(), 'type' => 'down', 'change' => '3'),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','595')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','436')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','262')->first(), 'type' => 'up', 'change' => '9'),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','682')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '11', 'unit' => Unit::where('draw_id','=','778')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '12', 'unit' => Unit::where('draw_id','=','678')->first(), 'type' => 'up', 'change' => '8'),
                array('rank' => '13', 'unit' => Unit::where('draw_id','=','768')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '14', 'unit' => Unit::where('draw_id','=','146')->first(), 'type' => 'down', 'change' => '9'),
                array('rank' => '15', 'unit' => Unit::where('draw_id','=','762')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '16', 'unit' => Unit::where('draw_id','=','752')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '17', 'unit' => Unit::where('draw_id','=','474')->first(), 'type' => 'down', 'change' => '5'),
                array('rank' => '18', 'unit' => Unit::where('draw_id','=','8')->first(), 'type' => 'down', 'change' => '9'),
                array('rank' => '19', 'unit' => Unit::where('draw_id','=','766')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '20', 'unit' => Unit::where('draw_id','=','333')->first(), 'type' => 'up', 'change' => '18'),
                array('rank' => '21', 'unit' => Unit::where('draw_id','=','482')->first(), 'type' => 'down', 'change' => '11'),
                array('rank' => '22', 'unit' => Unit::where('draw_id','=','764')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '23', 'unit' => Unit::where('draw_id','=','12')->first(), 'type' => 'down', 'change' => '7'),
                array('rank' => '24', 'unit' => Unit::where('draw_id','=','770')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '25', 'unit' => Unit::where('draw_id','=','20')->first(), 'type' => 'down', 'change' => '12'),
                array('rank' => '26', 'unit' => Unit::where('draw_id','=','218')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '27', 'unit' => Unit::where('draw_id','=','150')->first(), 'type' => 'down', 'change' => '10'),
                array('rank' => '28', 'unit' => Unit::where('draw_id','=','152')->first(), 'type' => 'down', 'change' => '13'),
                array('rank' => '29', 'unit' => Unit::where('draw_id','=','336')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '30', 'unit' => Unit::where('draw_id','=','772')->first(), 'type' => 'new', 'change' => ''),
            );
            $data['vote4'] = $vote;
            // cache
            Cache::forever($key, $vote);
        }
        $key = 'blade_vote_result_5';
        if (Cache::has($key)){
            $data['vote5'] = Cache::get($key);
        } else {
            $vote = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','866')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','650')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','965')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','898')->first(), 'type' => 'up', 'change' => '21'),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','896')->first(), 'type' => 'up', 'change' => '18'),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','895')->first(), 'type' => 'up', 'change' => '12'),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','897')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','610')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','750')->first(), 'type' => 'down', 'change' => '7'),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','296')->first(), 'type' => 'down', 'change' => '7'),
                array('rank' => '11', 'unit' => Unit::where('draw_id','=','894')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '12', 'unit' => Unit::where('draw_id','=','963')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '13', 'unit' => Unit::where('draw_id','=','148')->first(), 'type' => 'down', 'change' => '7'),
                array('rank' => '14', 'unit' => Unit::where('draw_id','=','682')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '15', 'unit' => Unit::where('draw_id','=','559')->first(), 'type' => 'down', 'change' => '10'),
                array('rank' => '16', 'unit' => Unit::where('draw_id','=','927')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '17', 'unit' => Unit::where('draw_id','=','678')->first(), 'type' => 'down', 'change' => '5'),
                array('rank' => '18', 'unit' => Unit::where('draw_id','=','762')->first(), 'type' => 'down', 'change' => '3'),
                array('rank' => '19', 'unit' => Unit::where('draw_id','=','766')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '20', 'unit' => Unit::where('draw_id','=','778')->first(), 'type' => 'down', 'change' => '9'),
                array('rank' => '21', 'unit' => Unit::where('draw_id','=','768')->first(), 'type' => 'down', 'change' => '8'),
                array('rank' => '22', 'unit' => Unit::where('draw_id','=','811')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '23', 'unit' => Unit::where('draw_id','=','436')->first(), 'type' => 'down', 'change' => '15'),
                array('rank' => '24', 'unit' => Unit::where('draw_id','=','474')->first(), 'type' => 'down', 'change' => '7'),
                array('rank' => '25', 'unit' => Unit::where('draw_id','=','146')->first(), 'type' => 'down', 'change' => '11'),
                array('rank' => '26', 'unit' => Unit::where('draw_id','=','764')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '27', 'unit' => Unit::where('draw_id','=','752')->first(), 'type' => 'down', 'change' => '11'),
                array('rank' => '28', 'unit' => Unit::where('draw_id','=','899')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '29', 'unit' => Unit::where('draw_id','=','770')->first(), 'type' => 'down', 'change' => '5'),
                array('rank' => '30', 'unit' => Unit::where('draw_id','=','772')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '31', 'unit' => Unit::where('draw_id','=','482')->first(), 'type' => 'down', 'change' => '10'),
                array('rank' => '32', 'unit' => Unit::where('draw_id','=','218')->first(), 'type' => 'down', 'change' => '6'),
                array('rank' => '33', 'unit' => Unit::where('draw_id','=','636')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '34', 'unit' => Unit::where('draw_id','=','262')->first(), 'type' => 'down', 'change' => '25'),
                array('rank' => '35', 'unit' => Unit::where('draw_id','=','428')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '36', 'unit' => Unit::where('draw_id','=','879')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '37', 'unit' => Unit::where('draw_id','=','595')->first(), 'type' => 'down', 'change' => '30'),
                array('rank' => '38', 'unit' => Unit::where('draw_id','=','274')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '39', 'unit' => Unit::where('draw_id','=','355')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '40', 'unit' => Unit::where('draw_id','=','864')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '41', 'unit' => Unit::where('draw_id','=','817')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '42', 'unit' => Unit::where('draw_id','=','819')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '43', 'unit' => Unit::where('draw_id','=','150')->first(), 'type' => 'down', 'change' => '16'),
                array('rank' => '44', 'unit' => Unit::where('draw_id','=','343')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '45', 'unit' => Unit::where('draw_id','=','152')->first(), 'type' => 'down', 'change' => '17'),
                array('rank' => '46', 'unit' => Unit::where('draw_id','=','278')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '47', 'unit' => Unit::where('draw_id','=','336')->first(), 'type' => 'down', 'change' => '18'),
                array('rank' => '48', 'unit' => Unit::where('draw_id','=','264')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '49', 'unit' => Unit::where('draw_id','=','628')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '50', 'unit' => Unit::where('draw_id','=','154')->first(), 'type' => 'new', 'change' => ''),
            );
            $data['vote5'] = $vote;
            // cache
            Cache::forever($key, $vote);
        }
        $key = 'blade_vote_result_6';
        if (Cache::has($key)){
            $data['vote6'] = Cache::get($key);
        } else {
            $vote = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','1011')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','866')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','650')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','296')->first(), 'type' => 'up', 'change' => '6'),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','965')->first(), 'type' => 'down', 'change' => '2'),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','1012')->first(), 'type' => 'up', 'change' => '40'),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','610')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','750')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','898')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','897')->first(), 'type' => 'down', 'change' => '3'),
                array('rank' => '11', 'unit' => Unit::where('draw_id','=','963')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '12', 'unit' => Unit::where('draw_id','=','1013')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '13', 'unit' => Unit::where('draw_id','=','896')->first(), 'type' => 'down', 'change' => '8'),
                array('rank' => '14', 'unit' => Unit::where('draw_id','=','1004')->first(), 'type' => 'up', 'change' => '18'),
                array('rank' => '15', 'unit' => Unit::where('draw_id','=','895')->first(), 'type' => 'down', 'change' => '9'),
                array('rank' => '16', 'unit' => Unit::where('draw_id','=','595')->first(), 'type' => 'up', 'change' => '21'),
                array('rank' => '17', 'unit' => Unit::where('draw_id','=','778')->first(), 'type' => 'up', 'change' => '3'),
                array('rank' => '18', 'unit' => Unit::where('draw_id','=','894')->first(), 'type' => 'down', 'change' => '7'),
                array('rank' => '19', 'unit' => Unit::where('draw_id','=','559')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '20', 'unit' => Unit::where('draw_id','=','148')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '21', 'unit' => Unit::where('draw_id','=','474')->first(), 'type' => 'up', 'change' => '3'),
                array('rank' => '22', 'unit' => Unit::where('draw_id','=','436')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '23', 'unit' => Unit::where('draw_id','=','682')->first(), 'type' => 'down', 'change' => '9'),
                array('rank' => '24', 'unit' => Unit::where('draw_id','=','762')->first(), 'type' => 'down', 'change' => '6'),
                array('rank' => '25', 'unit' => Unit::where('draw_id','=','1009')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '26', 'unit' => Unit::where('draw_id','=','879')->first(), 'type' => 'up', 'change' => '10'),
                array('rank' => '27', 'unit' => Unit::where('draw_id','=','752')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '28', 'unit' => Unit::where('draw_id','=','817')->first(), 'type' => 'up', 'change' => '13'),
                array('rank' => '29', 'unit' => Unit::where('draw_id','=','768')->first(), 'type' => 'down', 'change' => '16'),
                array('rank' => '30', 'unit' => Unit::where('draw_id','=','678')->first(), 'type' => 'down', 'change' => '13'),
                array('rank' => '31', 'unit' => Unit::where('draw_id','=','146')->first(), 'type' => 'down', 'change' => '6'),
                array('rank' => '32', 'unit' => Unit::where('draw_id','=','811')->first(), 'type' => 'down', 'change' => '10'),
                array('rank' => '33', 'unit' => Unit::where('draw_id','=','766')->first(), 'type' => 'down', 'change' => '14'),
                array('rank' => '34', 'unit' => Unit::where('draw_id','=','764')->first(), 'type' => 'down', 'change' => '8'),
                array('rank' => '35', 'unit' => Unit::where('draw_id','=','899')->first(), 'type' => 'down', 'change' => '7'),
                array('rank' => '36', 'unit' => Unit::where('draw_id','=','482')->first(), 'type' => 'down', 'change' => '5'),
                array('rank' => '37', 'unit' => Unit::where('draw_id','=','355')->first(), 'type' => 'up', 'change' => '2'),
                array('rank' => '38', 'unit' => Unit::where('draw_id','=','770')->first(), 'type' => 'down', 'change' => '9'),
                array('rank' => '39', 'unit' => Unit::where('draw_id','=','343')->first(), 'type' => 'up', 'change' => '5'),
                array('rank' => '40', 'unit' => Unit::where('draw_id','=','1023')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '41', 'unit' => Unit::where('draw_id','=','336')->first(), 'type' => 'up', 'change' => '6'),
                array('rank' => '42', 'unit' => Unit::where('draw_id','=','819')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '43', 'unit' => Unit::where('draw_id','=','772')->first(), 'type' => 'down', 'change' => '13'),
                array('rank' => '44', 'unit' => Unit::where('draw_id','=','1007')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '45', 'unit' => Unit::where('draw_id','=','351')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '46', 'unit' => Unit::where('draw_id','=','648')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '47', 'unit' => Unit::where('draw_id','=','636')->first(), 'type' => 'down', 'change' => '14'),
                array('rank' => '48', 'unit' => Unit::where('draw_id','=','347')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '49', 'unit' => Unit::where('draw_id','=','927')->first(), 'type' => 'down', 'change' => '33'),
                array('rank' => '50', 'unit' => Unit::where('draw_id','=','864')->first(), 'type' => 'down', 'change' => '10'),
                array('rank' => '51', 'unit' => Unit::where('draw_id','=','274')->first(), 'type' => 'down', 'change' => '13'),
                array('rank' => '52', 'unit' => Unit::where('draw_id','=','638')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '53', 'unit' => Unit::where('draw_id','=','264')->first(), 'type' => 'down', 'change' => '5'),
                array('rank' => '54', 'unit' => Unit::where('draw_id','=','262')->first(), 'type' => 'down', 'change' => '20'),
                array('rank' => '55', 'unit' => Unit::where('draw_id','=','286')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '56', 'unit' => Unit::where('draw_id','=','684')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '57', 'unit' => Unit::where('draw_id','=','428')->first(), 'type' => 'down', 'change' => '22'),
                array('rank' => '58', 'unit' => Unit::where('draw_id','=','926')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '59', 'unit' => Unit::where('draw_id','=','152')->first(), 'type' => 'down', 'change' => '14'),
                array('rank' => '60', 'unit' => Unit::where('draw_id','=','260')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '61', 'unit' => Unit::where('draw_id','=','349')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '62', 'unit' => Unit::where('draw_id','=','150')->first(), 'type' => 'down', 'change' => '19'),
                array('rank' => '63', 'unit' => Unit::where('draw_id','=','632')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '64', 'unit' => Unit::where('draw_id','=','924')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '65', 'unit' => Unit::where('draw_id','=','333')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '66', 'unit' => Unit::where('draw_id','=','815')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '67', 'unit' => Unit::where('draw_id','=','973')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '68', 'unit' => Unit::where('draw_id','=','154')->first(), 'type' => 'down', 'change' => '18'),
                array('rank' => '69', 'unit' => Unit::where('draw_id','=','916')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '70', 'unit' => Unit::where('draw_id','=','893')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '71', 'unit' => Unit::where('draw_id','=','625')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '72', 'unit' => Unit::where('draw_id','=','813')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '73', 'unit' => Unit::where('draw_id','=','881')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '74', 'unit' => Unit::where('draw_id','=','379')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '75', 'unit' => Unit::where('draw_id','=','272')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '76', 'unit' => Unit::where('draw_id','=','426')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '77', 'unit' => Unit::where('draw_id','=','686')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '78', 'unit' => Unit::where('draw_id','=','634')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '79', 'unit' => Unit::where('draw_id','=','630')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '80', 'unit' => Unit::where('draw_id','=','541')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '81', 'unit' => Unit::where('draw_id','=','375')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '82', 'unit' => Unit::where('draw_id','=','381')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '83', 'unit' => Unit::where('draw_id','=','496')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '84', 'unit' => Unit::where('draw_id','=','434')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '85', 'unit' => Unit::where('draw_id','=','156')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '86', 'unit' => Unit::where('draw_id','=','549')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '87', 'unit' => Unit::where('draw_id','=','628')->first(), 'type' => 'down', 'change' => '38'),
                array('rank' => '88', 'unit' => Unit::where('draw_id','=','688')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '89', 'unit' => Unit::where('draw_id','=','877')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '90', 'unit' => Unit::where('draw_id','=','967')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '91', 'unit' => Unit::where('draw_id','=','534')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '92', 'unit' => Unit::where('draw_id','=','280')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '93', 'unit' => Unit::where('draw_id','=','885')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '94', 'unit' => Unit::where('draw_id','=','640')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '95', 'unit' => Unit::where('draw_id','=','918')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '96', 'unit' => Unit::where('draw_id','=','478')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '97', 'unit' => Unit::where('draw_id','=','337')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '98', 'unit' => Unit::where('draw_id','=','338')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '99', 'unit' => Unit::where('draw_id','=','1019')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '100', 'unit' => Unit::where('draw_id','=','430')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '101', 'unit' => Unit::where('draw_id','=','543')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '102', 'unit' => Unit::where('draw_id','=','353')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '103', 'unit' => Unit::where('draw_id','=','593')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '104', 'unit' => Unit::where('draw_id','=','258')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '105', 'unit' => Unit::where('draw_id','=','809')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '106', 'unit' => Unit::where('draw_id','=','959')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '107', 'unit' => Unit::where('draw_id','=','971')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '108', 'unit' => Unit::where('draw_id','=','680')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '109', 'unit' => Unit::where('draw_id','=','587')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '110', 'unit' => Unit::where('draw_id','=','977')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '111', 'unit' => Unit::where('draw_id','=','334')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '112', 'unit' => Unit::where('draw_id','=','591')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '113', 'unit' => Unit::where('draw_id','=','598')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '114', 'unit' => Unit::where('draw_id','=','292')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '115', 'unit' => Unit::where('draw_id','=','975')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '116', 'unit' => Unit::where('draw_id','=','883')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '117', 'unit' => Unit::where('draw_id','=','220')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '118', 'unit' => Unit::where('draw_id','=','424')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '119', 'unit' => Unit::where('draw_id','=','504')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '120', 'unit' => Unit::where('draw_id','=','335')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '121', 'unit' => Unit::where('draw_id','=','276')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '122', 'unit' => Unit::where('draw_id','=','545')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '123', 'unit' => Unit::where('draw_id','=','887')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '124', 'unit' => Unit::where('draw_id','=','284')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '125', 'unit' => Unit::where('draw_id','=','432')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '126', 'unit' => Unit::where('draw_id','=','268')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '127', 'unit' => Unit::where('draw_id','=','1025')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '128', 'unit' => Unit::where('draw_id','=','831')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '129', 'unit' => Unit::where('draw_id','=','339')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '130', 'unit' => Unit::where('draw_id','=','345')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '131', 'unit' => Unit::where('draw_id','=','532')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '132', 'unit' => Unit::where('draw_id','=','547')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '133', 'unit' => Unit::where('draw_id','=','823')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '134', 'unit' => Unit::where('draw_id','=','444')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '135', 'unit' => Unit::where('draw_id','=','706')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '136', 'unit' => Unit::where('draw_id','=','476')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '137', 'unit' => Unit::where('draw_id','=','922')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '138', 'unit' => Unit::where('draw_id','=','979')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '139', 'unit' => Unit::where('draw_id','=','1021')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '140', 'unit' => Unit::where('draw_id','=','714')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '141', 'unit' => Unit::where('draw_id','=','401')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '142', 'unit' => Unit::where('draw_id','=','955')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '143', 'unit' => Unit::where('draw_id','=','266')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '144', 'unit' => Unit::where('draw_id','=','449')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '145', 'unit' => Unit::where('draw_id','=','294')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '146', 'unit' => Unit::where('draw_id','=','484')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '147', 'unit' => Unit::where('draw_id','=','969')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '148', 'unit' => Unit::where('draw_id','=','539')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '149', 'unit' => Unit::where('draw_id','=','1015')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '150', 'unit' => Unit::where('draw_id','=','448')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '151', 'unit' => Unit::where('draw_id','=','270')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '152', 'unit' => Unit::where('draw_id','=','716')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '153', 'unit' => Unit::where('draw_id','=','698')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '154', 'unit' => Unit::where('draw_id','=','957')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '155', 'unit' => Unit::where('draw_id','=','756')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '156', 'unit' => Unit::where('draw_id','=','583')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '157', 'unit' => Unit::where('draw_id','=','395')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '158', 'unit' => Unit::where('draw_id','=','961')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '159', 'unit' => Unit::where('draw_id','=','397')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '160', 'unit' => Unit::where('draw_id','=','282')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '161', 'unit' => Unit::where('draw_id','=','500')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '162', 'unit' => Unit::where('draw_id','=','606')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '163', 'unit' => Unit::where('draw_id','=','480')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '164', 'unit' => Unit::where('draw_id','=','492')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '165', 'unit' => Unit::where('draw_id','=','557')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '166', 'unit' => Unit::where('draw_id','=','136')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '167', 'unit' => Unit::where('draw_id','=','782')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '168', 'unit' => Unit::where('draw_id','=','827')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '169', 'unit' => Unit::where('draw_id','=','445')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '170', 'unit' => Unit::where('draw_id','=','710')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '171', 'unit' => Unit::where('draw_id','=','331')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '172', 'unit' => Unit::where('draw_id','=','696')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '173', 'unit' => Unit::where('draw_id','=','1017')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '174', 'unit' => Unit::where('draw_id','=','589')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '175', 'unit' => Unit::where('draw_id','=','134')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '176', 'unit' => Unit::where('draw_id','=','221')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '177', 'unit' => Unit::where('draw_id','=','602')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '178', 'unit' => Unit::where('draw_id','=','780')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '179', 'unit' => Unit::where('draw_id','=','585')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '180', 'unit' => Unit::where('draw_id','=','951')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '181', 'unit' => Unit::where('draw_id','=','790')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '182', 'unit' => Unit::where('draw_id','=','802')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '183', 'unit' => Unit::where('draw_id','=','124')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '184', 'unit' => Unit::where('draw_id','=','442')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '185', 'unit' => Unit::where('draw_id','=','288')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '186', 'unit' => Unit::where('draw_id','=','447')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '187', 'unit' => Unit::where('draw_id','=','393')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '188', 'unit' => Unit::where('draw_id','=','754')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '189', 'unit' => Unit::where('draw_id','=','391')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '190', 'unit' => Unit::where('draw_id','=','446')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '191', 'unit' => Unit::where('draw_id','=','502')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '192', 'unit' => Unit::where('draw_id','=','290')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '193', 'unit' => Unit::where('draw_id','=','399')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '194', 'unit' => Unit::where('draw_id','=','403')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '195', 'unit' => Unit::where('draw_id','=','953')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '196', 'unit' => Unit::where('draw_id','=','868')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '197', 'unit' => Unit::where('draw_id','=','829')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '198', 'unit' => Unit::where('draw_id','=','920')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '199', 'unit' => Unit::where('draw_id','=','223')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '200', 'unit' => Unit::where('draw_id','=','385')->first(), 'type' => 'new', 'change' => ''),
            );
            $data['vote6'] = $vote;
            // cache
            Cache::forever($key, $vote);
        }
        $key = 'blade_vote_result_7';
        if (Cache::has($key)){
            $data['vote7'] = Cache::get($key);
        } else {
            $vote = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','1011')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','866')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','650')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','965')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','1064')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','1205')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','296')->first(), 'type' => 'down', 'change' => '3'),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','896')->first(), 'type' => 'up', 'change' => '5'),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','1266')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','1123')->first(), 'type' => 'up', 'change' => '35'),
                array('rank' => '11', 'unit' => Unit::where('draw_id','=','898')->first(), 'type' => 'down', 'change' => '2'),
                array('rank' => '12', 'unit' => Unit::where('draw_id','=','1012')->first(), 'type' => 'down', 'change' => '6'),
                array('rank' => '13', 'unit' => Unit::where('draw_id','=','897')->first(), 'type' => 'down', 'change' => '3'),
                array('rank' => '14', 'unit' => Unit::where('draw_id','=','895')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '15', 'unit' => Unit::where('draw_id','=','1268')->first(), 'type' => 'up', 'change' => '99'),
                array('rank' => '16', 'unit' => Unit::where('draw_id','=','894')->first(), 'type' => 'up', 'change' => '2'),
                array('rank' => '17', 'unit' => Unit::where('draw_id','=','1215')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '18', 'unit' => Unit::where('draw_id','=','963')->first(), 'type' => 'down', 'change' => '7'),
                array('rank' => '19', 'unit' => Unit::where('draw_id','=','750')->first(), 'type' => 'down', 'change' => '11'),
                array('rank' => '20', 'unit' => Unit::where('draw_id','=','778')->first(), 'type' => 'down', 'change' => '3'),
                array('rank' => '21', 'unit' => Unit::where('draw_id','=','610')->first(), 'type' => 'down', 'change' => '14'),
                array('rank' => '22', 'unit' => Unit::where('draw_id','=','1122')->first(), 'type' => 'up', 'change' => '39'),
                array('rank' => '23', 'unit' => Unit::where('draw_id','=','682')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '24', 'unit' => Unit::where('draw_id','=','1112')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '25', 'unit' => Unit::where('draw_id','=','1148')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '26', 'unit' => Unit::where('draw_id','=','474')->first(), 'type' => 'down', 'change' => '26'),
                array('rank' => '27', 'unit' => Unit::where('draw_id','=','1004')->first(), 'type' => 'down', 'change' => '13'),
                array('rank' => '28', 'unit' => Unit::where('draw_id','=','1012')->first(), 'type' => 'down', 'change' => '22'),
                array('rank' => '29', 'unit' => Unit::where('draw_id','=','148')->first(), 'type' => 'down', 'change' => '9'),
                array('rank' => '30', 'unit' => Unit::where('draw_id','=','879')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '31', 'unit' => Unit::where('draw_id','=','559')->first(), 'type' => 'down', 'change' => '12'),
                array('rank' => '32', 'unit' => Unit::where('draw_id','=','1214')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '33', 'unit' => Unit::where('draw_id','=','1267')->first(), 'type' => 'up', 'change' => '18'),
                array('rank' => '34', 'unit' => Unit::where('draw_id','=','811')->first(), 'type' => 'down', 'change' => '2'),
                array('rank' => '35', 'unit' => Unit::where('draw_id','=','1216')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '36', 'unit' => Unit::where('draw_id','=','1115')->first(), 'type' => 'up', 'change' => '39'),
                array('rank' => '37', 'unit' => Unit::where('draw_id','=','762')->first(), 'type' => 'down', 'change' => '13'),
                array('rank' => '38', 'unit' => Unit::where('draw_id','=','1201')->first(), 'type' => 'up', 'change' => '154'),
                array('rank' => '39', 'unit' => Unit::where('draw_id','=','768')->first(), 'type' => 'down', 'change' => '10'),
                array('rank' => '40', 'unit' => Unit::where('draw_id','=','1013')->first(), 'type' => 'down', 'change' => '28'),
            );
            $data['vote7'] = $vote;
            // cache
            Cache::forever($key, $vote);
        }
        $key = 'blade_vote_result_8';
        if (Cache::has($key)){
            $data['vote8'] = Cache::get($key);
        } else {
            $vote['all'] = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','1011')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','866')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','1064')->first(), 'type' => 'up', 'change' => '2'),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','965')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','1277')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','650')->first(), 'type' => 'down', 'change' => '3'),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','1205')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','1378')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','1273')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','1012')->first(), 'type' => 'up', 'change' => '18'),
                array('rank' => '11', 'unit' => Unit::where('draw_id','=','296')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '12', 'unit' => Unit::where('draw_id','=','1382')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '13', 'unit' => Unit::where('draw_id','=','1123')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '14', 'unit' => Unit::where('draw_id','=','1266')->first(), 'type' => 'down', 'change' => '5'),
                array('rank' => '15', 'unit' => Unit::where('draw_id','=','1325')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '16', 'unit' => Unit::where('draw_id','=','963')->first(), 'type' => 'up', 'change' => '2'),
                array('rank' => '17', 'unit' => Unit::where('draw_id','=','1112')->first(), 'type' => 'up', 'change' => '7'),
                array('rank' => '18', 'unit' => Unit::where('draw_id','=','750')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '19', 'unit' => Unit::where('draw_id','=','474')->first(), 'type' => 'up', 'change' => '7'),
                array('rank' => '20', 'unit' => Unit::where('draw_id','=','1354')->first(), 'type' => 'new', 'change' => ''),
            );
            $vote['fire'] = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','1064')->first(), 'type' => '', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','1382')->first(), 'type' => '', 'change' => ''),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','474')->first(), 'type' => '', 'change' => ''),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','778')->first(), 'type' => '', 'change' => ''),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','1338')->first(), 'type' => '', 'change' => ''),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','1115')->first(), 'type' => '', 'change' => ''),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','894')->first(), 'type' => '', 'change' => ''),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','4')->first(), 'type' => '', 'change' => ''),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','146')->first(), 'type' => '', 'change' => ''),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','762')->first(), 'type' => '', 'change' => ''),
            );
            $vote['water'] = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','650')->first(), 'type' => '', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','1012')->first(), 'type' => '', 'change' => ''),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','1200')->first(), 'type' => '', 'change' => ''),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','1271')->first(), 'type' => '', 'change' => ''),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','148')->first(), 'type' => '', 'change' => ''),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','895')->first(), 'type' => '', 'change' => ''),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','879')->first(), 'type' => '', 'change' => ''),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','752')->first(), 'type' => '', 'change' => ''),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','1162')->first(), 'type' => '', 'change' => ''),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','8')->first(), 'type' => '', 'change' => ''),
            );
            $vote['wind'] = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','1325')->first(), 'type' => '', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','963')->first(), 'type' => '', 'change' => ''),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','1112')->first(), 'type' => '', 'change' => ''),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','559')->first(), 'type' => '', 'change' => ''),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','1122')->first(), 'type' => '', 'change' => ''),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','1339')->first(), 'type' => '', 'change' => ''),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','12')->first(), 'type' => '', 'change' => ''),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','896')->first(), 'type' => '', 'change' => ''),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','682')->first(), 'type' => '', 'change' => ''),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','766')->first(), 'type' => '', 'change' => ''),
            );
            $vote['light'] = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','1011')->first(), 'type' => '', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','296')->first(), 'type' => '', 'change' => ''),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','1123')->first(), 'type' => '', 'change' => '', 'end' => false),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','1266')->first(), 'type' => '', 'change' => '', 'end' => true),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','750')->first(), 'type' => '', 'change' => ''),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','897')->first(), 'type' => '', 'change' => ''),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','16')->first(), 'type' => '', 'change' => ''),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','290')->first(), 'type' => '', 'change' => ''),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','684')->first(), 'type' => '', 'change' => ''),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','768')->first(), 'type' => '', 'change' => ''),
            );
            $vote['dark'] = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','965')->first(), 'type' => '', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','1277')->first(), 'type' => '', 'change' => ''),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','1273')->first(), 'type' => '', 'change' => ''),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','1354')->first(), 'type' => '', 'change' => ''),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','610')->first(), 'type' => '', 'change' => ''),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','898')->first(), 'type' => '', 'change' => ''),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','20')->first(), 'type' => '', 'change' => ''),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','1268')->first(), 'type' => '', 'change' => ''),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','1270')->first(), 'type' => '', 'change' => ''),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','817')->first(), 'type' => '', 'change' => ''),
            );
            $vote['none'] = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','866')->first(), 'type' => '', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','1205')->first(), 'type' => '', 'change' => ''),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','1378')->first(), 'type' => '', 'change' => ''),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','1215')->first(), 'type' => '', 'change' => ''),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','1004')->first(), 'type' => '', 'change' => ''),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','1013')->first(), 'type' => '', 'change' => ''),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','436')->first(), 'type' => '', 'change' => ''),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','355')->first(), 'type' => '', 'change' => ''),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','1009')->first(), 'type' => '', 'change' => ''),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','218')->first(), 'type' => '', 'change' => ''),
            );
            $data['vote8'] = $vote;
            // cache
            Cache::forever($key, $vote);
        }
        $key = 'blade_vote_result_9';
        if (Cache::has($key)){
            $data['vote9'] = Cache::get($key);
        } else {
            $vote = array(
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','1011')->first(), 'type' => '', 'change' => ''),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','650')->first(), 'type' => '', 'change' => ''),
                array('rank' => '12', 'unit' => Unit::where('draw_id','=','898')->first(), 'type' => '', 'change' => ''),
            );
            $data['vote9'] = $vote;
            // cache
            Cache::forever($key, $vote);
        }
        $key = 'blade_vote_result_10';
        if (Cache::has($key)){
            $data['vote10'] = Cache::get($key);
        } else {
            $vote = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','1502')->first(), 'type' => 'up', 'change' => '5'),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','1011')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','866')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','1509')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','1064')->first(), 'type' => 'down', 'change' => '2'),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','965')->first(), 'type' => 'down', 'change' => '2'),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','1445')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','1277')->first(), 'type' => 'down', 'change' => '3'),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','1479')->first(), 'type' => 'up', 'change' => '7'),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','296')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '11', 'unit' => Unit::where('draw_id','=','1205')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '12', 'unit' => Unit::where('draw_id','=','1325')->first(), 'type' => 'up', 'change' => '3'),
                array('rank' => '13', 'unit' => Unit::where('draw_id','=','1266')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '14', 'unit' => Unit::where('draw_id','=','1429')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '15', 'unit' => Unit::where('draw_id','=','1012')->first(), 'type' => 'down', 'change' => '5'),
                array('rank' => '16', 'unit' => Unit::where('draw_id','=','898')->first(), 'type' => 'up', 'change' => '4'),
                array('rank' => '17', 'unit' => Unit::where('draw_id','=','1382')->first(), 'type' => 'down', 'change' => '5'),
                array('rank' => '18', 'unit' => Unit::where('draw_id','=','1273')->first(), 'type' => 'down', 'change' => '9'),
                array('rank' => '19', 'unit' => Unit::where('draw_id','=','1478')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '20', 'unit' => Unit::where('draw_id','=','1481')->first(), 'type' => 'new', 'change' => ''),
            );
            $data['vote10'] = $vote;
            // cache
            Cache::forever($key, $vote);
        }
        $key = 'blade_vote_result_11';
        if (Cache::has($key)){
            $data['vote11'] = Cache::get($key);
        } else {
            $vote = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','1821')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','1011')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','1502')->first(), 'type' => 'down', 'change' => '2'),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','866')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','1064')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','1810')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','965')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','1277')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','1445')->first(), 'type' => 'down', 'change' => '2'),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','1509')->first(), 'type' => 'down', 'change' => '6'),
                array('rank' => '11', 'unit' => Unit::where('draw_id','=','650')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '11', 'unit' => Unit::where('draw_id','=','1012')->first(), 'type' => 'up', 'change' => '4'),
                array('rank' => '13', 'unit' => Unit::where('draw_id','=','1479')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '14', 'unit' => Unit::where('draw_id','=','1851')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '14', 'unit' => Unit::where('draw_id','=','1205')->first(), 'type' => 'down', 'change' => '3'),
                array('rank' => '16', 'unit' => Unit::where('draw_id','=','1429')->first(), 'type' => 'down', 'change' => '2'),
                array('rank' => '17', 'unit' => Unit::where('draw_id','=','1266')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '18', 'unit' => Unit::where('draw_id','=','296')->first(), 'type' => 'down', 'change' => '8'),
                array('rank' => '18', 'unit' => Unit::where('draw_id','=','1325')->first(), 'type' => 'down', 'change' => '6'),
                array('rank' => '20', 'unit' => Unit::where('draw_id','=','474')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '21', 'unit' => Unit::where('draw_id','=','750')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '22', 'unit' => Unit::where('draw_id','=','1788')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '23', 'unit' => Unit::where('draw_id','=','895')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '24', 'unit' => Unit::where('draw_id','=','1809')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '25', 'unit' => Unit::where('draw_id','=','1481')->first(), 'type' => 'down', 'change' => '5'),
                array('rank' => '26', 'unit' => Unit::where('draw_id','=','1818')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '27', 'unit' => Unit::where('draw_id','=','1478')->first(), 'type' => 'down', 'change' => '8'),
                array('rank' => '28', 'unit' => Unit::where('draw_id','=','1273')->first(), 'type' => 'down', 'change' => '10'),
                array('rank' => '29', 'unit' => Unit::where('draw_id','=','1382')->first(), 'type' => 'down', 'change' => '12'),
                array('rank' => '30', 'unit' => Unit::where('draw_id','=','1013')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '31', 'unit' => Unit::where('draw_id','=','1123')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '32', 'unit' => Unit::where('draw_id','=','778')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '33', 'unit' => Unit::where('draw_id','=','896')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '34', 'unit' => Unit::where('draw_id','=','1454')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '34', 'unit' => Unit::where('draw_id','=','1271')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '36', 'unit' => Unit::where('draw_id','=','898')->first(), 'type' => 'down', 'change' => '20'),
                array('rank' => '37', 'unit' => Unit::where('draw_id','=','1783')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '38', 'unit' => Unit::where('draw_id','=','8')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '39', 'unit' => Unit::where('draw_id','=','894')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '40', 'unit' => Unit::where('draw_id','=','20')->first(), 'type' => 'new', 'change' => ''),
            );
            $data['vote11'] = $vote;
            // cache
            Cache::forever($key, $vote);
        }
        $key = 'blade_vote_result_12';
        if (Cache::has($key)){
            $data['vote12'] = Cache::get($key);
        } else {
            $vote = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','1011')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','1821')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','866')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','1502')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','1925')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','965')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','1064')->first(), 'type' => 'down', 'change' => '2'),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','1445')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','1277')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','1810')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '11', 'unit' => Unit::where('draw_id','=','1509')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '12', 'unit' => Unit::where('draw_id','=','1966')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '13', 'unit' => Unit::where('draw_id','=','650')->first(), 'type' => 'down', 'change' => '2'),
                array('rank' => '14', 'unit' => Unit::where('draw_id','=','1479')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '15', 'unit' => Unit::where('draw_id','=','1205')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '16', 'unit' => Unit::where('draw_id','=','1012')->first(), 'type' => 'down', 'change' => '5'),
                array('rank' => '17', 'unit' => Unit::where('draw_id','=','1429')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '18', 'unit' => Unit::where('draw_id','=','1266')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '19', 'unit' => Unit::where('draw_id','=','1325')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '20', 'unit' => Unit::where('draw_id','=','296')->first(), 'type' => 'down', 'change' => '2'),
                array('rank' => '21', 'unit' => Unit::where('draw_id','=','474')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '22', 'unit' => Unit::where('draw_id','=','750')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '23', 'unit' => Unit::where('draw_id','=','1382')->first(), 'type' => 'up', 'change' => '6'),
                array('rank' => '24', 'unit' => Unit::where('draw_id','=','1809')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '25', 'unit' => Unit::where('draw_id','=','894')->first(), 'type' => 'up', 'change' => '14'),
                array('rank' => '26', 'unit' => Unit::where('draw_id','=','1481')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '27', 'unit' => Unit::where('draw_id','=','1013')->first(), 'type' => 'up', 'change' => '3'),
                array('rank' => '28', 'unit' => Unit::where('draw_id','=','1788')->first(), 'type' => 'down', 'change' => '6'),
                array('rank' => '29', 'unit' => Unit::where('draw_id','=','1273')->first(), 'type' => 'down', 'change' => '1'),
                array('rank' => '30', 'unit' => Unit::where('draw_id','=','895')->first(), 'type' => 'down', 'change' => '7'),
                array('rank' => '31', 'unit' => Unit::where('draw_id','=','1478')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '32', 'unit' => Unit::where('draw_id','=','1965')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '33', 'unit' => Unit::where('draw_id','=','896')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '33', 'unit' => Unit::where('draw_id','=','1851')->first(), 'type' => 'down', 'change' => '19'),
                array('rank' => '35', 'unit' => Unit::where('draw_id','=','1967')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '36', 'unit' => Unit::where('draw_id','=','1951')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '37', 'unit' => Unit::where('draw_id','=','1454')->first(), 'type' => 'down', 'change' => '3'),
                array('rank' => '38', 'unit' => Unit::where('draw_id','=','778')->first(), 'type' => 'down', 'change' => '6'),
                array('rank' => '39', 'unit' => Unit::where('draw_id','=','1378')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '40', 'unit' => Unit::where('draw_id','=','1123')->first(), 'type' => 'down', 'change' => '9'),
                array('rank' => '40', 'unit' => Unit::where('draw_id','=','1818')->first(), 'type' => 'down', 'change' => '14'),
                array('rank' => '42', 'unit' => Unit::where('draw_id','=','8')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '43', 'unit' => Unit::where('draw_id','=','1112')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '44', 'unit' => Unit::where('draw_id','=','1947')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '45', 'unit' => Unit::where('draw_id','=','1969')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '46', 'unit' => Unit::where('draw_id','=','1781')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '47', 'unit' => Unit::where('draw_id','=','1422')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '48', 'unit' => Unit::where('draw_id','=','1010')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '48', 'unit' => Unit::where('draw_id','=','1004')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '50', 'unit' => Unit::where('draw_id','=','762')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '50', 'unit' => Unit::where('draw_id','=','1883')->first(), 'type' => 'new', 'change' => ''),
            );
            $data['vote12'] = $vote;
            // cache
            Cache::forever($key, $vote);
        }
        $key = 'blade_vote_result_13';
        //if (Cache::has($key)){
        //    $data['vote13'] = Cache::get($key);
        //} else {
            $vote['fav'] = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','2347')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','1502')->first(), 'type' => 'up', 'change' => '2'),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','2182')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','1821')->first(), 'type' => 'down', 'change' => '2'),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','1064')->first(), 'type' => 'up', 'change' => '2'),
                array('rank' => '6', 'unit' => Unit::where('draw_id','=','2195')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','1277')->first(), 'type' => 'up', 'change' => '2'),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','2343')->first(), 'type' => 'new', 'change' => ''),
                array('rank' => '9', 'unit' => Unit::where('draw_id','=','1925')->first(), 'type' => 'down', 'change' => '4'),
                array('rank' => '10', 'unit' => Unit::where('draw_id','=','1445')->first(), 'type' => 'down', 'change' => '2'),
                array('rank' => '11', 'unit' => Unit::where('draw_id','=','1509')->first(), 'type' => 'equal', 'change' => ''),
                array('rank' => '12', 'unit' => Unit::where('draw_id','=','1809')->first(), 'type' => 'down', 'change' => '2'),
                array('rank' => '15', 'unit' => Unit::where('draw_id','=','2359')->first(), 'type' => 'up', 'change' => '7'),
                array('rank' => '16', 'unit' => Unit::where('draw_id','=','2183')->first(), 'type' => 'up', 'change' => '2'),
                array('rank' => '17', 'unit' => Unit::where('draw_id','=','898')->first(), 'type' => 'down', 'change' => '5'),
                array('rank' => '18', 'unit' => Unit::where('draw_id','=','2237')->first(), 'type' => 'up', 'change' => '1'),
                array('rank' => '21', 'unit' => Unit::where('draw_id','=','1012')->first(), 'type' => 'down', 'change' => '5'),
            );
            $vote['tsundere'] = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','2347')->first(), 'type' => '', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','898')->first(), 'type' => '', 'change' => ''),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','1277')->first(), 'type' => '', 'change' => ''),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','1925')->first(), 'type' => '', 'change' => ''),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','866')->first(), 'type' => '', 'change' => ''),
                array('rank' => '8', 'unit' => Unit::where('draw_id','=','1064')->first(), 'type' => '', 'change' => ''),
                array('rank' => '13', 'unit' => Unit::where('draw_id','=','1012')->first(), 'type' => '', 'change' => ''),
                array('rank' => '15', 'unit' => Unit::where('draw_id','=','1478')->first(), 'type' => '', 'change' => ''),
                array('rank' => '16', 'unit' => Unit::where('draw_id','=','1429')->first(), 'type' => '', 'change' => ''),
                array('rank' => '32', 'unit' => Unit::where('draw_id','=','382')->first(), 'type' => '', 'change' => ''),
            );
            $vote['wife'] = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','1502')->first(), 'type' => '', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','897')->first(), 'type' => '', 'change' => ''),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','2347')->first(), 'type' => '', 'change' => ''),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','1925')->first(), 'type' => '', 'change' => ''),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','1115')->first(), 'type' => '', 'change' => ''),
                array('rank' => '19', 'unit' => Unit::where('draw_id','=','894')->first(), 'type' => '', 'change' => ''),
                array('rank' => '20', 'unit' => Unit::where('draw_id','=','1339')->first(), 'type' => '', 'change' => ''),
                array('rank' => '21', 'unit' => Unit::where('draw_id','=','895')->first(), 'type' => '', 'change' => ''),
                array('rank' => '38', 'unit' => Unit::where('draw_id','=','899')->first(), 'type' => '', 'change' => ''),
                array('rank' => '39', 'unit' => Unit::where('draw_id','=','382')->first(), 'type' => '', 'change' => ''),
            );
            $vote['cook'] = array(
                array('rank' => '1', 'unit' => Unit::where('draw_id','=','2347')->first(), 'type' => '', 'change' => ''),
                array('rank' => '2', 'unit' => Unit::where('draw_id','=','1162')->first(), 'type' => '', 'change' => ''),
                array('rank' => '3', 'unit' => Unit::where('draw_id','=','1112')->first(), 'type' => '', 'change' => ''),
                array('rank' => '4', 'unit' => Unit::where('draw_id','=','1502')->first(), 'type' => '', 'change' => ''),
                array('rank' => '5', 'unit' => Unit::where('draw_id','=','1479')->first(), 'type' => '', 'change' => ''),
                array('rank' => '7', 'unit' => Unit::where('draw_id','=','897')->first(), 'type' => '', 'change' => ''),
                array('rank' => '11', 'unit' => Unit::where('draw_id','=','2417')->first(), 'type' => '', 'change' => ''),
                array('rank' => '13', 'unit' => Unit::where('draw_id','=','382')->first(), 'type' => '', 'change' => ''),
            );
            $data['vote13'] = $vote;
            // cache
            Cache::forever($key, $vote);
        //}
        return view('min.voteResult', $data);
    }

    public function mark($type){
        $data = array('type' => $type);
        return view('min.mark', $data);
    }

    public function clearCache(){
        Cache::forget('blade_unitlist');
        echo 'unitlist cache cleared<br/>';
        Cache::forget('blade_event_egg');
        Cache::forget('blade_event_material');
        Cache::forget('blade_event_schedule');
        echo 'event cache cleared<br/>';
        Cache::forget('blade_questlist');
        echo 'questlist cache cleared<br/>';
        Cache::forget('api_skilln');
        Cache::forget('api_skilll');
        Cache::forget('api_skilla');
        Cache::forget('api_skillp');
        Cache::forget('api_skillln');
        Cache::forget('api_skilllp');
        echo 'skill list cache cleared<br/>';
    }

    public function rank(){
        $key = 'blade_rank';
        if (Cache::has($key)){
            $data = Cache::get($key);
        } else {
            $rank = UserRank::where('fix_id','!=','0')->get();
            $data = compact('rank');
            // cache
            Cache::forever($key, $data);
        }
        return view('rank', $data);
    }

    public function story(){
        $key = 'blade_unitlist';
        if (Cache::has($key)){
            $unitlist = Cache::get($key);
        } else {
            $unitlist = Unit::where('fix_id','!=','0')->orderBy('draw_id')->get();
            // cache
            $expiresAt = new Carbon('next friday');
            Cache::put($key, $unitlist, $expiresAt);
        }
        $data = compact('unitlist');
        $units = Unit::where('fix_id','!=','0')->orderBy('draw_id', 'asc')->get();
        foreach($units as $unit){
            $data['units'][$unit->draw_id]['obj'] = $unit;
            $translate = $unit->getTranslate();
            if($translate != null)
                $data['units'][$unit->draw_id]['text'] = $translate->text;
            else
                $data['units'][$unit->draw_id]['text'] = '未有翻譯';
        }
        return view('story', $data);
    }
}
