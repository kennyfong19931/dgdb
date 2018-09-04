<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Unit;
use App\Models\Quest;
use App\Models\Area;
use App\Models\Translate;
use App\Http\Requests;
use App\Util\FunctionUtil;
use App\Util\CacheUtil;
use \Cache;
use \DB;
use Carbon\Carbon;

class PagesController extends Controller
{
    private $cacheUtil;
    private $function;

    public function __construct(){
        $this->cacheUtil = new CacheUtil();
        $this->function = new FunctionUtil;
    }

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
        $data = array();
        $unitlist = $this->cacheUtil->unitlist();
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
                $weekday = $this->function->getCycleDay($event->cycle_date_type);
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
        return view('min.unit', $this->cacheUtil->unit($id));
    }

    public function quest($id){
        return view('min.quest', $this->cacheUtil->quest($id));
    }

    public function area($id){
        return view('min.area', $this->cacheUtil->area($id));
    }

    public function unitlist(){
        $unitlist = $this->cacheUtil->unitlist();
        $data = compact('unitlist');
        return view('min.unitlist', $data);
    }

    public function questlist(){
        return view('min.questlist', $this->cacheUtil->questlist());
    }

    public function skill($type){
        $data = array('type' => $type);
        return view('min.skill', $data);
    }

    public function voteResult(){
        $data = array();
        for($i = 1; $i < 14; $i++){
            $data['vote'.$i] = $this->cacheUtil->voteResult($i);
        }
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
        Cache::forget('api_skill_n');
        Cache::forget('api_skill_l');
        Cache::forget('api_skill_a');
        Cache::forget('api_skill_p');
        Cache::forget('api_skill_ln');
        Cache::forget('api_skill_lp');
        echo 'skill list cache cleared<br/>';
    }

    public function rank(){
        return view('rank', $this->cacheUtil->rank());
    }

    public function story(){
        $unitlist = $this->cacheUtil->unitlist();
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
