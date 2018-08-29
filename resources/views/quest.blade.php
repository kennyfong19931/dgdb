<?php
    $imageUtil = new \App\Util\ImageUtil;
    $title = preg_replace('/\[([A-Fa-f0-9]{6}|w{3})\]/','',$quest->quest_name);
    $title = preg_replace('/\[\-\]/','',$title);
?>
@extends('nav')

@section('title')
{{ $title }} - Divine Gate 資料庫
@stop

@section('social_netowrk')
<meta property="og:title" content="{{ $title }}"/>
<meta property="og:description" content="{{ $title }}資料"/>
<meta property="og:locale" content="zh_HK">
<meta property="og:url" content="{{ URL::asset('/') }}"/>
<meta property="og:site_name" content="Divine Gate 資料庫" />
<meta property="fb:app_id" content="1140845152610532" />
<meta property="og:image" content="{{ URL::asset('/img/favicon.png') }}" />
@stop

@section('style')
.questRequirement{
    margin: 0px;
    padding-left: 20px;
    list-style-type: square;
}
.questRequirement li{
    list-style-type: square;
}
.ability{
    list-style-type: none;
}

#translation{
    position: relative;
}
dl{
    -webkit-margin-before: 0em;
    -webkit-margin-after: 0em;
}
dd, dt{
    display: block;
    float: left;
}
dt {
    font-weight: bold;
    clear: left;
    margin-right: 5px;
}
dd{
    -webkit-margin-start: 0px;
}
@stop

@section('script')
$(".card-content").each(function(i, obj){
    $(obj).html($(obj).html().replace(/\[([A-Fa-f0-9]{6}|w{3})\]/g,'<span style="color:#$1">'));
    $(obj).html($(obj).html().replace(/\[\-\]/g,'</span>'));
});
$(".quest-name").each(function(i, obj){
    $(obj).html($(obj).html().replace(/\[([A-Fa-f0-9]{6}|w{3})\]/g,'<span style="color:#$1">'));
    $(obj).html($(obj).html().replace(/\[\-\]/g,'</span>'));
});
$('ul.tabs').tabs();
$('ul.tabs').tabs('select_tab', 'floor1');
$('.tooltipped').tooltip({delay: 50});

function parseTranslate(){
    $("#translation").html($("#translation").html().replace(/\[\[([0-9]+)\|([^\]]+)\]\]/g, '<a href="{{URL::asset('/')}}unit/$1">$2</a>' ));
}
parseTranslate();
@stop

@section('content')
    <div class="container">
        <div class="row">
            <nav class="primary">
                <div class="nav-wrapper">
                    <div class="col s12 breadcrumb-div-outer">
                      <div class="breadcrumb-div-inner">
                        <span class="breadcrumb">{{$area_cate_name}}</span>
                        <a href="{{ action('PagesController@area', ['id' => $area_id]) }}" class="breadcrumb">{{$area_name}}</a>
                        <span class="breadcrumb quest-name">{{$quest->quest_name}}</span>
                      </div>
                    </div>
                </div>
            </nav>
            @if(isset($quest_requirement))
            <div class="col s12">
                <div class="hoverable card-panel blue lighten-4 blue-text text-darken-3">
                    <ul class="questRequirement">
                        @foreach($quest_requirement as $requirement)
                            @if(is_array($requirement))
                                @if(isset($requirement['fix_unit']) || isset($requirement['link_unit']) )
                                    <li>固定Unit:<br/>
                                        @if(isset($requirement['fix_unit'][0]))
                                            └ 隊長: <?=$imageUtil->geticon(4, $requirement['fix_unit'][0]['unit'], 25)?> Lv.{{$requirement['fix_unit'][0]['lv']}}
                                            @if($requirement['fix_unit'][0]['plus_hp'] > 0 || $requirement['fix_unit'][0]['plus_atk'] > 0)
                                                (HP+{{$requirement['fix_unit'][0]['plus_hp']}}/ATK+{{$requirement['fix_unit'][0]['plus_atk']}})
                                            @endif
                                        @endif
                                        @if(isset($requirement['link_unit'][0]))
                                            [Link:<?=$imageUtil->geticon(4, $requirement['link_unit'][0]['unit'], 25)?> Lv.{{$requirement['link_unit'][0]['lv']}}
                                            @if($requirement['link_unit'][0]['plus_hp'] > 0 || $requirement['link_unit'][0]['plus_atk'] > 0)
                                                (HP+{{$requirement['link_unit'][0]['plus_hp']}}/ATK+{{$requirement['link_unit'][0]['plus_atk']}})
                                            @endif
                                            ]
                                        @endif
                                        @if(isset($requirement['fix_unit'][0]) || isset($requirement['link_unit'][0]) )
                                            <br/>
                                        @endif
                                        @for($i = 1; $i < 4; $i++)
                                            @if(isset($requirement['fix_unit'][$i]))
                                                └ 隊員: <?=$imageUtil->geticon(4, $requirement['fix_unit'][$i]['unit'], 25)?> Lv.{{$requirement['fix_unit'][$i]['lv']}}
                                                @if($requirement['fix_unit'][$i]['plus_hp'] > 0 || $requirement['fix_unit'][$i]['plus_atk'] > 0)
                                                    (HP+{{$requirement['fix_unit'][$i]['plus_hp']}}/ATK+{{$requirement['fix_unit'][$i]['plus_atk']}})
                                                @endif
                                            @endif
                                            @if(isset($requirement['link_unit'][$i]))
                                                [Link:<?=$imageUtil->geticon(4, $requirement['link_unit'][$i]['unit'], 25)?> Lv.{{$requirement['link_unit'][$i]['lv']}}
                                                @if($requirement['link_unit'][$i]['plus_hp'] > 0 || $requirement['link_unit'][$i]['plus_atk'] > 0)
                                                    (HP+{{$requirement['link_unit'][$i]['plus_hp']}}/ATK+{{$requirement['link_unit'][$i]['plus_atk']}})
                                                @endif
                                                ]
                                            @endif
                                            @if(isset($requirement['fix_unit'][$i]) || isset($requirement['link_unit'][$i]) )
                                                <br/>
                                            @endif
                                        @endfor
                                        @if(isset($requirement['fix_unit'][4]))
                                            └ 好友: <?=$imageUtil->geticon(4, $requirement['fix_unit'][4]['unit'], 25)?> Lv.{{$requirement['fix_unit'][4]['lv']}}
                                            @if($requirement['fix_unit'][4]['plus_hp'] > 0 || $requirement['fix_unit'][4]['plus_atk'] > 0)
                                                (HP+{{$requirement['fix_unit'][4]['plus_hp']}}/ATK+{{$requirement['fix_unit'][4]['plus_atk']}})
                                            @endif
                                        @endif
                                        @if(isset($requirement['link_unit'][4]))
                                            [Link:<?=$imageUtil->geticon(4, $requirement['link_unit'][4]['unit'], 25)?> Lv.{{$requirement['link_unit'][4]['lv']}}
                                            @if($requirement['link_unit'][4]['plus_hp'] > 0 || $requirement['link_unit'][4]['plus_atk'] > 0)
                                                (HP+{{$requirement['link_unit'][4]['plus_hp']}}/ATK+{{$requirement['link_unit'][4]['plus_atk']}})
                                            @endif
                                            ]
                                        @endif
                                        @if(isset($requirement['fix_unit'][4]) || isset($requirement['link_unit'][4]) )
                                            <br/>
                                        @endif
                                    </li>
                                @else
                                    <li>首次通關可獲得 Lv.{{$requirement['lv']}}的<?=$imageUtil->geticon(1, $requirement['unit'], 30)?></li>
                                @endif
                            @else
                                <li>{{$requirement}}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
            <div class="col s12">
                <div class="hoverable card-panel grey darken-1 white-text" id="translation">
                    <div class="section">{{$quest->story}}</div>@if(isset($storycn))<div class="divider"></div><div class="section" id="translatecn">{{$storycn}}</div>@endif
                </div>
            </div>
            <div class="col s12">
                <div class="hoverable card-panel grey white-text">
                    <div class="row" style="margin-bottom:0;">
                        <div class="col s6 m4">所需體力: {{$quest->quest_stamina}}</div>
                        <div class="col s6 m4">所需券: <img src="{{URL::asset('/img/icon/ticket.png')}}" style="width:15px;height:15px;"> {{$quest->quest_ticket}}</div>
                        <div class="col s6 m4">所需鑰匙: {{$quest->quest_key}} @if(isset($area_key)) ({{$area_key}}) @endif</div>
                        <div class="col s6 m4">可獲金錢: <img src="{{URL::asset('/img/icon/money.png')}}" style="width:15px;height:15px;"> {{$quest->clear_money}}</div>
                        <div class="col s6 m4">可獲經驗值: {{$quest->clear_exp}}</div>
                        <div class="col s6 m4">可獲硬幣: <img src="{{URL::asset('/img/icon/coin.png')}}" style="width:15px;height:15px;"> {{$quest->clear_stone}}</div>
                        <div class="col s12 m4">Link Unit可獲親密度: {{($quest->clear_link_point)/100}}%</div>
                    </div>
                </div>
            </div>
            @if(isset($noData))
                <div class="col s12">
                    <div class="hoverable">
                        <div class="card-title red white-text">
                            BOSS
                        </div>
                        <div class="card-content black white-text">
                            <div class="row">
                                <div class="col s2"><?=$imageUtil->getIcon(1, $boss, 50)?></div>
                                <div class="col s10">
                                    @if(isset($abilities))
                                        特性:
                                        <ul class="ability">
                                            @foreach($abilities as $ability)
                                                <li>{{$ability->name}}
                                                    @if($ability->detail != "")
                                                        &nbsp-&nbsp{{$ability->detail}}&nbsp
                                                    @endif
                                                    <?=$ability->getDetailCn()?>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s12">
                    <div class="hoverable">
                        <div class="card-panel amber lighten-1">
                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> 由於官方API封鎖, 未能提供其他資訊
                        </div>
                    </div>
                </div>
            @else
                <div class="col s12">
                    <div class="hoverable">
                        <div class="card-title red white-text">
                            BOSS
                        </div>
                        <div class="card-content black white-text">
                            <div class="row">
                                <div class="col s2">Icon</div>
                                <div class="col s2">HP</div>
                                <div class="col s2">ATK</div>
                                <div class="col s2">DEF</div>
                                <div class="col s1">CD</div>
                                <div class="col s2">掉落</div>
                            </div>
                            <div class="row">
                                @foreach($boss as $unit)
                                    <div class="col s12 top-border"></div>
                                    <div class="col s2 "><?=$imageUtil->getIcon(1, $unit['unit'], 50)?></div>
                                    <div class="col s2 cell">{{$unit['hp']}}</div>
                                    <div class="col s2 cell">{{$unit['atk']}}</div>
                                    <div class="col s2 cell">{{$unit['def']}}</div>
                                    <div class="col s1 cell">{{$unit['cd']}}</div>
                                    <div class="col s2"><?=$imageUtil->getIcon(1, $unit['drop'], 50)?></div>
                                    <div class="col s1"></div>
                                    @if(isset($unit['ability']) && sizeof($unit['ability']) > 0)
                                        <div class="col s12 m2 actionTiming">
                                            特性
                                        </div>
                                        <div class="col s12 m10">
                                            <ul class="ability">
                                                @foreach($unit['ability'] as $ability)
                                                    <li>{{$ability->name}}
                                                        @if($ability->detail != "")
                                                            &nbsp-&nbsp{{$ability->detail}}&nbsp
                                                        @endif
                                                        <?=$ability->getDetailCn()?>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if(array_get($unit, 'act_first'))
                                        <div class="col s12 m2">
                                            先制攻擊
                                        </div>
                                        <div class="col s12 m10">
                                            @if($unit['act_first']['name'] != null)
                                                <strong>{{$unit['act_first']['name']}}</strong> -
                                            @endif
                                            {{$unit['act_first']['detail']}}
                                            @if(isset($unit['act_first']['status_ailment']))
                                                @foreach($unit['act_first']['status_ailment'] as $ailment)
                                                    ; <?=$ailment?>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endif
                                    @for($i = 0; $i < 9; $i++)
                                        @if(array_get($unit, 'act_table'.$i))
                                            <div class="col s12 m2 actionTiming">
                                            @if(array_get($unit, 'act_table'.$i.'.timing_type') === 1)
                                                HP≧0%
                                            @elseif(array_get($unit, 'act_table'.$i.'.timing_type') === 2)
                                                HP≦{{array_get($unit, 'act_table'.$i.'.timing_param1')}}%
                                            @elseif(array_get($unit, 'act_table'.$i.'.timing_type') === 3)
                                                {{array_get($unit, 'act_table'.$i.'.timing_param1')}}回合後
                                            @endif
                                            </div>
                                            <div class="col s12 m10">
                                            <ul>
                                            <?php $count = 1 ?>
                                            @if(array_get($unit, 'act_table'.$i.'.action_type') === 1)
                                                隨機使用以下技能：<br/>
                                            @elseif(array_get($unit, 'act_table'.$i.'.action_type') === 2)
                                                順序循環使用以下技能：<br/>
                                            @endif
                                            @foreach(array_get($unit, 'act_table'.$i.'.moves') as $move)
                                                <li>
                                                @if(array_get($unit, 'act_table'.$i.'.action_type') === 1)
                                                    &nbsp▪&nbsp
                                                @elseif(array_get($unit, 'act_table'.$i.'.action_type') === 2)
                                                    {{$count}}.&nbsp<?php $count++ ?>
                                                @endif
                                                @if($move['name'] != null)
                                                    <strong>{{$move['name']}}</strong> -
                                                @endif
                                                {{$move['detail']}}
                                                @if(isset($move['status_ailment']))
                                                    @foreach($move['status_ailment'] as $ailment)
                                                        ; <?=$ailment?>
                                                    @endforeach
                                                @endif
                                                </li>
                                            @endforeach
                                            </ul>
                                            </div>
                                        @endif
                                    @endfor
                                    @if(array_get($unit, 'act_dead'))
                                        <div class="col s12 m2">
                                            死後攻擊
                                        </div>
                                        <div class="col s12 m10">
                                            @if($unit['act_dead']['name'] != null)
                                                <strong>{{$unit['act_dead']['name']}}</strong> -
                                            @endif
                                            {{$unit['act_dead']['detail']}}
                                            @if(isset($unit['act_dead']['status_ailment']))
                                                @foreach($unit['act_dead']['status_ailment'] as $ailment)
                                                    ; <?=$ailment?>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s12">
                    <div class="hoverable">
                        <div class="card-title purple darken-1 white-text">
                            小怪
                        </div>
                        <div class="card-content black white-text">
                            <div class="row">
                                <div class="col s2">小怪</div>
                                <div class="col s2">HP</div>
                                <div class="col s2">ATK</div>
                                <div class="col s2">DEF</div>
                                <div class="col s1">CD</div>
                                <div class="col s2">掉落</div>
                                <div class="col s1"></div>
                            </div>
                            <div class="row">
                                @foreach($enemy as $unit)
                                    <div class="col s2"><?=$imageUtil->getIcon(1, $unit['unit'], 50)?></div>
                                    <div class="col s2">{{$unit['hp']}}</div>
                                    <div class="col s2">{{$unit['atk']}}</div>
                                    <div class="col s2">{{$unit['def']}}</div>
                                    <div class="col s1">{{$unit['cd']}}</div>
                                    <div class="col s2"><?=$imageUtil->getIcon(1, $unit['drop'], 50)?></div>
                                        @if(array_get($unit, 'act_first'))
                                            <div class="col s12 m2">
                                                先制攻擊
                                            </div>
                                            <div class="col s12 m10">
                                                @if($unit['act_first']['name'] != null)
                                                    <strong>{{$unit['act_first']['name']}}</strong> -
                                                @endif
                                                {{$unit['act_first']['detail']}}
                                                @if(isset($unit['act_first']['status_ailment']))
                                                    @foreach($unit['act_first']['status_ailment'] as $ailment)
                                                        ; <?=$ailment?>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endif
                                        @for($i = 0; $i < 9; $i++)
                                        @if(array_get($unit, 'act_table'.$i))
                                            <div class="col s12 m2 actionTiming">
                                            @if(array_get($unit, 'act_table'.$i.'.timing_type') === 1)
                                                HP≧0%
                                            @elseif(array_get($unit, 'act_table'.$i.'.timing_type') === 2)
                                                HP≦{{array_get($unit, 'act_table'.$i.'.timing_param1')}}%
                                            @elseif(array_get($unit, 'act_table'.$i.'.timing_type') === 3)
                                                {{array_get($unit, 'act_table'.$i.'.timing_param1')}}回合後
                                            @endif
                                            </div>
                                            <div class="col s12 m10">
                                            <ul>
                                            @foreach(array_get($unit, 'act_table'.$i.'.moves') as $move)
                                                <?php $count = 1 ?><li>
                                                @if(array_get($unit, 'act_table'.$i.'.action_type') === 1)
                                                    &nbsp▪&nbsp
                                                @elseif(array_get($unit, 'act_table'.$i.'.action_type') === 2)
                                                    {{$count}}.&nbsp<?php $count++ ?>
                                                @endif
                                                @if($move['name'] != null)
                                                    <strong>{{$move['name']}}</strong> -
                                                @endif
                                                {{$move['detail']}}
                                                @if(isset($move['status_ailment']))
                                                    @foreach($move['status_ailment'] as $ailment)
                                                        ; <?=$ailment?>
                                                    @endforeach
                                                @endif
                                                </li>
                                            @endforeach
                                            </ul>
                                            </div>
                                        @endif
                                    @endfor
                                    @if(array_get($unit, 'act_dead'))
                                        <div class="col s12 m2">
                                            死後攻擊
                                        </div>
                                        <div class="col s12 m10">
                                            @if($unit['act_dead']['name'] != null)
                                                <strong>{{$unit['act_dead']['name']}}</strong> -
                                            @endif
                                            {{$unit['act_dead']['detail']}}
                                            @if(isset($unit['act_dead']['status_ailment']))
                                                @foreach($unit['act_dead']['status_ailment'] as $ailment)
                                                    ; <?=$ailment?>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col s12">
                    <div class="hoverable">
                        <div class="card-title teal darken-1 white-text">
                            格子分佈
                        </div>
                        <div class="card-content black white-text">
                            <div class="row">
                                <div class="col s1">格</div>
                                <div class="col s5">出現 Unit</div>
                                <div class="col s3">陷阱</div>
                                <div class="col s3">金錢</div>
                            </div>
                            <div class="row floor">
                                <div class="col s12 floor">
                                    <?php $count = 1; ?>
                                    @foreach($floors as $floor)
                                        <div id="floor{{$count}}" class="col s12 floor">
                                            <div class="row floor"><div class="col s12 floor">
                                            @for($i = 1; $i < 8; $i++)
                                                <div class="row floor">
                                                    <div class="col s1">
                                                        @if($i == 7)
                                                            !
                                                        @else
                                                            {{$i}}★
                                                        @endif
                                                    </div>
                                                    <div class="col s5">
                                                        @if(isset($floor[$i]['enemy']))
                                                            @foreach($floor[$i]['enemy'] as $enemy)
                                                                <?=$imageUtil->getIcon(1, $enemy, 50)?>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="col s3">
                                                        @if(isset($floor[$i]['trap']))
                                                            @foreach($floor[$i]['trap'] as $trap)
                                                                <?=$imageUtil->getTrap($trap)?>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="col s3">
                                                        @if($floor[$i]['money']['min']->fix_id != 0 && $floor[$i]['money']['max']->fix_id)
                                                            <img src="{{URL::asset('/')}}img/panel/{{$floor[$i]['money']['icon']}}.png" width="50px" height="50px">
                                                            @if($floor[$i]['money']['min']->effective_value != 0)
                                                                {{$floor[$i]['money']['min']->effective_value}}円 ~ {{$floor[$i]['money']['max']->effective_value}}円
                                                            @else
                                                                {{$floor[$i]['money']['max']->effective_value}}円
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            @endfor
                                        </div></div>
                                        </div>
                                        <?php $count++; ?>
                                    @endforeach
                                </div>
                                <div class="col s12 floor">
                                    <ul class="tabs grey darken-3 white-text">
                                        <li class="tab col s2 disabled"><a href="">層數</a></li>
                                        @for($i = 1; $i <= sizeof($floors); $i++)
                                            @if($i === 1)
                                                <li class="tab col s2"><a class="active" href="#floor{{$i}}">{{$i}}</a></li>
                                            @else
                                                <li class="tab col s2"><a href="#floor{{$i}}">{{$i}}</a></li>
                                            @endif
                                        @endfor
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- mission list removed (Achievement data not up to date)-->
            @endif
        </div>
    </div>
@stop
