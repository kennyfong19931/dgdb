<?php
    $imageUtil = new \App\Util\ImageUtil;
    $function = new \App\Util\FunctionUtil;
    $series = explode(' ', $unit->series);
?>
@extends('nav')

@section('title')
{{ $unit->name }} - Divine Gate 資料庫
@stop

@section('social_netowrk')
<meta property="og:title" content="{{ $unit->name }}"/>
<meta property="og:description" content="{{ $unit->draw_id }} - {{ $unit->name }}
{{ $unit->detail }}"/>
<meta property="og:locale" content="zh_HK">
<meta property="og:url" content="{{ URL::asset('/') }}"/>
<meta property="og:site_name" content="Divine Gate 資料庫" />
<meta property="fb:app_id" content="1140845152610532" />
<meta property="og:image" content="{{ URL::asset('/img/favicon.png') }}" />
<meta property="og:image" content="<?=$imageUtil->getUnitLarge($unit->draw_id)?>" />
<meta property="og:image" content="<?=$imageUtil->getIconLink($function->getTriId($unit->draw_id))?>" />
@stop

@section('style')
#charaBG{
                background: URL({{ URL::asset('img/chara_bg.jpg') }});
                background-repeat: no-repeat;
                background-size: cover;
            }

            input{
                width: initial !important;
                margin: 0 !important;
            }
            #translation{
                position: relative;
            }
            #translateButton{
                position: absolute;
                top: 0px;
                right: 0px;
                height: 25px;
                line-height: 25px;
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
$('.unit-wrapper').pushpin({ top:$('#charaBG').height()+$('#nav').height(), offset: $('nav').height() });

$(".card-content").each(function(i, obj){
    $(obj).html($(obj).html().replace(/\[([A-Fa-f0-9]{6}|w{3})\]/g,'<span style="color:#$1">'));
    $(obj).html($(obj).html().replace(/\[\-\]/g,'</span>'));
});

function getUnitValue(level, levelMax, levelMin, type, valueMin, valueMax, valueCurve = 0){
    if(levelMin === levelMax){
        return valueMax;
    } else {
        switch(valueCurve){
        	case 0:
        	case 1:
        	case 2:
        		pow = 1;
        		break;
        	case 3:
        		pow = 2.5;
        		break;
        	case 4:
        	case 5:
        	case 6:
        		pow = 0.7;
        		break;
        	case 7:
        	case 8:
        	case 9:
        		pow = 1.5;
        		break;
        	default:
        		return '';
        }
        diff = levelMax - levelMin;
        levelDiff = level - levelMin;
        return Math.floor(valueMin + ((valueMax - valueMin) * Math.pow((levelDiff/diff), pow)));
    }
}
function getLinkUnitValue(partyCost, rare, elem, type, level){
    v = 1+(partyCost*0.2)+((rare+1)*(rare+1));
    @if($unit->link_enable == 2)
    switch(type){
    	case 0:
    		pow = {{$link_atk}}/100;
    		break;
    	case 2:
    		pow = {{$link_hp}}/100;
    		break;
    }
    @endif
    return Math.floor(v*pow*(1+(level*0.03)));
}
function getLimitOverValue(grow, max, typeMax, level, type = null){
    switch(grow){
    	case 0:
    		grow = 0;
    		break;
    	case 1:
    		grow = 1;
    		break;
    	case 2:
    		grow = 0.7;
    		break;
    	case 3:
    		grow = 1.5;
    		break;
    }
    if(type == null){
    	return Math.round(typeMax*Math.pow((level/max),grow)*100)/100;
    } else if(type == 'charm'){
    	return Math.floor(typeMax*Math.pow((level/max),grow)*10) / 10;
    } else {
    	return max;
    }
}

$("#unitLv").bind('keyup mouseup',function(){
    if($("#unitLv").val() >= {{$unit->level_min}} && $("#unitLv").val() <= {{$unit->level_max}}){
        $("#unitHp").text(getUnitValue($("#unitLv").val(), {{$unit->level_max}}, 1, 2, {{$unit->base_hp_min}}, {{$unit->base_hp_max}}, {{$unit->base_hp_curve}}));
    	$("#unitAtk").text(getUnitValue($("#unitLv").val(), {{$unit->level_max}}, 1, 0, {{$unit->base_attack_min}}, {{$unit->base_attack_max}}, {{$unit->base_attack_curve}}));
    	$("#unitExp").text(getUnitValue($("#unitLv").val(), {{$unit->level_max}}, 1, 3, 0, {{$unit->exp_total}}, {{$unit->exp_total_curve}}));
    	$("#unitSales").text(getUnitValue($("#unitLv").val(), {{$unit->level_max}}, 1, 5,  {{$unit->sales_min}}, {{$unit->sales_max}}, {{$unit->sales_curve}}));
    	$("#unitBlendExp").text(getUnitValue($("#unitLv").val(), {{$unit->level_max}}, 1, 4, {{$unit->blend_exp_min}}, {{$unit->blend_exp_max}}, {{$unit->blend_exp_curve}}));
    }
});
$("#unitLinkLv").bind('keyup mouseup',function(){
    if($("#unitLinkLv").val() >= {{$unit->level_min}} && $("#unitLinkLv").val() <= {{$unit->level_max}}){
        $("#unitLinkHp").text(getLinkUnitValue({{$unit->party_cost}}, {{$unit->rare}}, {{$unit->element}}, 2, $("#unitLinkLv").val()));
    	$("#unitLinkAtk").text(getLinkUnitValue({{$unit->party_cost}}, {{$unit->rare}}, {{$unit->element}}, 0, $("#unitLinkLv").val()));
    }
});
$("#limitOverLv").bind('keyup mouseup',function(){
    if($("#limitOverLv").val() >= 1 && $("#limitOverLv").val() <= {{$limit_over_max}}){
        $("#limitOverHp").text(getLimitOverValue({{$limit_grow}}, {{$limit_over_max}}, {{$limit_over_max_hp}}, $("#limitOverLv").val()));
        $("#limitOverAtk").text(getLimitOverValue({{$limit_grow}}, {{$limit_over_max}}, {{$limit_over_max_atk}}, $("#limitOverLv").val()));
        $("#limitOverCost").text(Math.floor(getLimitOverValue({{$limit_grow}}, {{$limit_over_max}}, {{$limit_over_max_cost}}, $("#limitOverLv").val())));
        $("#limitOverCharm").text(getLimitOverValue({{$limit_grow}}, {{$limit_over_max}}, {{$limit_over_max_charm}}, $("#limitOverLv").val(), 'charm'));
    }
});
$("#limitOverLv2").bind('keyup mouseup',function(){
    if($("#limitOverLv2").val() >= 1 && $("#limitOverLv2").val() <= {{$limit_over_max}}){
        $("#limitOverHp").text(getLimitOverValue({{$limit_grow}}, {{$limit_over_max}}, {{$limit_over_max_hp}}, $("#limitOverLv2").val()));
        $("#limitOverAtk").text(getLimitOverValue({{$limit_grow}}, {{$limit_over_max}}, {{$limit_over_max_atk}}, $("#limitOverLv2").val()));
        $("#limitOverCost").text(Math.floor(getLimitOverValue({{$limit_grow}}, {{$limit_over_max}}, {{$limit_over_max_cost}}, $("#limitOverLv2").val())));
        $("#limitOverCharm").text(getLimitOverValue({{$limit_grow}}, {{$limit_over_max}}, {{$limit_over_max_charm}}, $("#limitOverLv2").val(), 'charm'));
    }
});
$("#unitHp").text(getUnitValue({{$unit->level_max}}, {{$unit->level_max}}, 1, 2, {{$unit->base_hp_min}}, {{$unit->base_hp_max}}, {{$unit->base_hp_curve}}));
$("#unitAtk").text(getUnitValue({{$unit->level_max}}, {{$unit->level_max}}, 1, 0, {{$unit->base_attack_min}}, {{$unit->base_attack_max}}, {{$unit->base_attack_curve}}));
$("#unitExp").text(getUnitValue({{$unit->level_max}}, {{$unit->level_max}}, 1, 3, 0, {{$unit->exp_total}}, {{$unit->exp_total_curve}}));
$("#unitSales").text(getUnitValue({{$unit->level_max}}, {{$unit->level_max}}, 1, 5,  {{$unit->sales_min}}, {{$unit->sales_max}}, {{$unit->sales_curve}}));
$("#unitBlendExp").text(getUnitValue({{$unit->level_max}}, {{$unit->level_max}}, 1, 4, {{$unit->blend_exp_min}}, {{$unit->blend_exp_max}}, {{$unit->blend_exp_curve}}));
$("#unitLinkHp").text(getLinkUnitValue({{$unit->party_cost}}, {{$unit->rare}}, {{$unit->element}}, 2, {{$unit->level_max}}));
$("#unitLinkAtk").text(getLinkUnitValue({{$unit->party_cost}}, {{$unit->rare}}, {{$unit->element}}, 0, {{$unit->level_max}}));
$("#limitOverHp").text(getLimitOverValue({{$limit_grow}}, {{$limit_over_max}}, {{$limit_over_max_hp}}, 1));
$("#limitOverAtk").text(getLimitOverValue({{$limit_grow}}, {{$limit_over_max}}, {{$limit_over_max_atk}}, 1));
$("#limitOverCost").text(Math.floor(getLimitOverValue({{$limit_grow}}, {{$limit_over_max}}, {{$limit_over_max_cost}}, 1)));
$("#limitOverCharm").text(getLimitOverValue({{$limit_grow}}, {{$limit_over_max}}, {{$limit_over_max_charm}}, 1, 'charm'));

$('.tooltipped').tooltip({delay: 50});

function parseTranslate(){
    $("#translation").html($("#translation").html().replace(/\[\[([0-9]+)\|([^\]]+)\]\]/g, '<a href="{{URL::asset('/')}}unit/$1">$2</a>' ));
}
@if(isset($detailcn))
$('.newTranslate').hide();
$('#translateButton').text('更新翻譯');
$("#translatecn").html("{{$detailcn}}");
$("#detailcn").html("{{$detailcn}}");
@else
$('.updateTranslate').hide();
@endif
parseTranslate();
$('#translateButton').leanModal();
$( "#translateFormSubmit" ).click(function() {
    Materialize.toast('翻譯提交中', 2000);
    $('#detailcn').val($('#detailcn').val().replace(/\r\n|\r|\n/g,''));
    $('#detailcn').val($('#detailcn').val().replace(/,/g,'，'));
    $('#detailcn').val($('#detailcn').val().replace(/\?/g,'？'));
    $('#detailcn').val($('#detailcn').val().replace(/!/g,'！'));
    $('#detailcn').val($('#detailcn').val().replace(/:/g,'：'));
    $('#detailcn').val($('#detailcn').val().replace(/;/g,'；'));
    $('#detailcn').val($('#detailcn').val().replace(/・/g,'・'));
    $.ajax({
        type: 'POST',
        url: '{{action('TranslateController@unit', ['id' => $unit->draw_id])}}',
        data: $("#translateForm").serialize(),
        dataType: 'json',
        success: function(data){
            if(data.success){
                Materialize.toast('<i class="fa fa-check" aria-hidden="true"></i>&nbsp翻譯已提交', 4000, 'green');
                if($("#translatecn").html().length == 0){
                    $('.newTranslate').each(function(i, obj){
                        $(obj).hide();
                    });
                    $('.updateTranslate').each(function(i, obj){
                        $(obj).show();
                    });
                    $('#translateButton').text('更新翻譯');
                }
                $("#translatecn").html($('#detailcn').val());
                parseTranslate();
                $('#translateButton').leanModal();
            }else{
                Materialize.toast('<i class="fa fa-exclamation" aria-hidden="true"></i>&nbsp&nbsp發生錯誤', 4000, 'red');
            }
        },
        error: function(xhr, type){
            Materialize.toast('<i class="fa fa-exclamation" aria-hidden="true"></i>&nbsp&nbsp發生錯誤', 4000, 'red');
        }
    });
});
@stop

@section('content')
<div class="grey darken-4">
    <div class="container valign-wrapper" id="charaBG">
        <img src="<?=$imageUtil->getUnitLarge($unit->draw_id)?>" class="center-align" id="unitinf{{$unit->size}}" alt="{{$unit->draw_id}} {{$unit->name}}">
    </div>
</div>
<div class="row fluid unit-wrapper primary">
    <div class="container title">
        <?=$imageUtil->getIcon(1, $unit, 50)?> <span class="title"> {{ $unit->draw_id.' - '.$unit->name }} </span>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col s12 m6">
            <div class="hoverable">
                <div class="card-title grey white-text">
                    基本資料
                </div>
                <div class="card-content black white-text">
                    <div class="row" style="margin-bottom: 0;">
                        <div class="col s6">
                            Rare:
                            @for($i = 0; $i < $unit->rare+1; $i++)★@endfor
                        </div>
                        <div class="col s6">
                            屬性: <?=$imageUtil->getElement($unit->element, 18)?>
                        </div>
                        <div class="col s6">
                            種族: {{ $function->getKind($unit->kind) }}@if($unit->sub_kind != '無指定') / {{$function->getKind($unit->sub_kind)}}@endif
                        </div>
                        <div class="col s6">
                            隊伍 Cost: {{ $unit->party_cost }}
                        </div>
                        <div class="col s12">
                            系列: <?php
                                    foreach($series as $str){
                                        echo '<a href="'.action('PagesController@unitlist').'#'.base64_encode($str).'">'.$str.'</a>  ';
                                    }
                                ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12 m6">
            <div class="hoverable">
                <div class="card-title grey white-text">
                    各等級數值
                </div>
                <div class="card-content black white-text">
                    <div class="row" style="margin-bottom: 0;">
                        <div class="valign-wrapper">
                            <div class="col s4 valign">
                                Lv. <input id="unitLv" type="number" min="1" max="{{ $unit->level_max }}" value="{{ $unit->level_max }}">
                            </div>
                            <div class="col s8">
                                HP: <span id="unitHp"></span><br/>
                                ATK: <span id="unitAtk"></span><br/>
                                所需經驗值: <span id="unitExp"></span><br/>
                                售價:  <img src="{{URL::asset('/img/icon/money.png')}}" style="width:15px;height:15px;"> <span id="unitSales"></span><br/> <img src="{{URL::asset('/img/icon/unit_point.png')}}" style="width:15px;height:15px;" alt="Unit Point">{{$unit->sales_unitpoint}}
                                飼料經: <span id="unitBlendExp"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12">
            <div class="hoverable">
                <div class="card-panel grey darken-1 white-text" id="translation">
                    <div class="section">{{ $unit->detail }}</div><div class="divider updateTranslate"></div><div class="section updateTranslate" id="translatecn"></div>
                    <a class="waves-effect waves-light btn" id="translateButton" data-target="translateModal">提供翻譯</a>
                </div>
            </div>
        </div>
        <div class="col s12 l6">
            <div class="hoverable">
                <div class="card-title purple darken-1 white-text">
                    隊長技能(LS)@if($unit->skill_leader != 0) - {{ $lsName }}@endif
                </div>
                <div class="card-content black white-text">
                    @if($unit->skill_leader != 0)
                        <div class="section">{{ $lsDetail }}</div><div class="divider"></div><div class="section">{{ $lsDetailCn }}</div>
                    @else
                        無
                    @endif
                </div>
            </div>
        </div>
        <div class="col s12 l6">
            <div class="hoverable">
                <div class="card-title red darken-1 white-text">
                    主動技能(AS)@if($unit->skill_limitbreak != 0) - {{ $asName }}@endif
                </div>
                <div class="card-content black white-text">
                    @if($unit->skill_limitbreak != 0)
                        <div class="row" style="margin-bottom: 0;">
                            <div class="col s2 center-align">
                                <div class="section">冷卻回合:<br/>{{ $asMax }}<br/>⬇<br/>{{ $asMin }}</div>
                            </div>
                            <div class="col s10">
                                <div class="section">{{ $asDetail }}</div><div class="divider"></div><div class="section"><?=$asDetailCn?></div>
                            </div>
                            <div class="col s12">
                                <div class="divider"></div>
                                擁有此技能的Unit:<br/>
                                @foreach($sameAS as $temp)
                                    <?=$imageUtil->getIcon(1, $temp, 50)?>
                                @endforeach
                            </div>
                        </div>
                    @else
                        無
                    @endif
                </div>
            </div>
        </div>
        <div class="col s12"></div>
        <div class="col s12 l6">
            <div class="hoverable">
                <div class="card-title green darken-1 white-text">
                    <div class="right">
                      @for($i = 4; $i >= 0; $i--)
                        @if($ns1Card[$i] != 0)
                            <div class="right"><?=$imageUtil->getElement($ns1Card[$i],25)?></div>
                        @endif
                      @endfor
                    </div>
                    普通技能1(NS1) - {{ $ns1Name }}
                </div>
                <div class="card-content black white-text">

                    <div class="section">{{ $ns1Detail }}</div><div class="divider"></div><div class="section"><?=$ns1DetailCn?></div>
                </div>
            </div>
        </div>
            @if($unit->skill_active1 != 0)
                <div class="col s12 l6">
                    <div class="hoverable">
                        <div class="card-title green darken-1 white-text">
                            <div class="right">
                              @for($i = 4; $i >= 0; $i--)
                                @if($ns2Card[$i] != 0)
                                  <div class="right"><?=$imageUtil->getElement($ns2Card[$i],25)?></div>
                                @endif
                              @endfor
                            </div>
                            普通技能2(NS2) - {{$ns2Name}}
                        </div>
                        <div class="card-content black white-text">
                            <div class="section">{{$ns2Detail}}</div><div class="divider"></div><div class="section"><?=$ns2DetailCn?></div>
                        </div>
                    </div>
                </div>
            @endif
            @if($unit->skill_passive != 0)
                <div class="col s12 l6">
                    <div class="hoverable">
                        <div class="card-title orange darken-1 white-text">
                            被動技能(PS) - {{$psName}}
                        </div>
                        <div class="card-content black white-text">
                            <div class="section">{{$psDetail}}</div><div class="divider"></div><div class="section"><?=$psDetailCn?></div>
                        </div>
                    </div>
                </div>
            @endif
        <div class="col s12 l6">
            <div class="hoverable">
                <div class="card-title grey white-text">
                    Link (限界突破為Lv.0時)
                </div>
                <div class="card-content black white-text">
		            @if($unit->link_enable == 2)
                        <div class="row" style="margin-bottom: 0.5rem;">
                            <div class="col s2 m3">Link Bonus</div>
                            <div class="col s4 m4">Lv. <input id="unitLinkLv" type="number" min="1" max="{{$unit->level_max}}" value="{{$unit->level_max}}"></div>
                            <div class="col s6 m5">HP: <span id="unitLinkHp"></span>+加蛋數量x2<br/>ATK: <span id="unitLinkAtk"></span>+加蛋數量</div>
                            <div class="col s12"></div>
                            <div class="col s2 m3">Race Bonus</div>
                            <div class="col s10 m9"><?=$race_bouns?></div>
                        </div>
                        <div class="row green darken-1" style="margin-bottom: 0;">
                            @if($unit->link_skill_active != 0)
                                <div class="col s12 m4 l5">Link Skill - <strong>{{$lnsName}}</strong></div>
                                    <div class="col s12 m8 l7 right">{{$lnsDetail}}<br/>{{$lnsDetailCn}}</div>
                                    <div class="col s12 m4">發動機率: {{$lnsOdds/100}}% ~
                                @if($lnsOdds*2 > 10000)
                                    100%
                                @else
                                    {{($lnsOdds*2/100)}}%
                                @endif
                                </div>
                            @else
                                <div class="col s4">Link Skill</div><div class="col s8">無</div>
                            @endif
                        </div>
                        <div class="row orange darken-1" style="margin-bottom: 0.5rem;">
                            @if($unit->link_skill_passive != 0)
                                <div class="col s4">Link Passive - <strong>{{$lpsName}}</strong></div>
                                <div class="col s8">{{$lpsDetail}}<br/>{{$lpsDetailCn}}</div>
                            @else
                                <div class="col s4">Link Passive</div><div class="col s8">無</div>
                            @endif
                        </div>
                        <div class="row" style="margin-bottom: 0;">
                            <div class="col s3">Link方法</div><div class="col s9">
                            @foreach($linkPart as $id)
                                @if($id->fix_id > 0)
                                    <?=$imageUtil->getIcon(1, $id, 50)?>
                                @endif
                            @endforeach
                            + <img src="{{URL::asset('/img/icon/money.png')}}" style="width:15px;height:15px;">{{$unit->link_money}}</div>
                            <div class="col s12"></div>
                            <div class="col s3">解Link方法</div><div class="col s9">
                            @foreach($delLinkPart as $id)
                                @if($id->fix_id > 0)
                                    <?=$imageUtil->getIcon(1, $id, 50)?>
                                @endif
                            @endforeach
                             <img src="{{URL::asset('/img/icon/money.png')}}" style="width:15px;height:15px;">{{$unit->link_del_money}}</div>
                        </div>
                    @else
                        不適用
                    @endif
                </div>
            </div>
        </div>
        <div class="col s12 l6">
            <div class="hoverable">
                <div class="card-title grey white-text">
                    限界突破
                </div>
                <div class="card-content black white-text">
                    @if($limit_over_max === 0)
                        不適用
                    @else
                        <div class="row" style="margin-bottom: 0;">
                            <div class="col s6 m2 l3">可突破次數</div><div class="col s6 m2 l1">{{$limit_over_max}}</div>
                            <div class="col s6 m4 l4">突破所需unit point: <img src="{{URL::asset('/img/icon/unit_point.png')}}" style="width:15px;height:15px;" alt="Unit Point">{{$unit->limit_over_unitpoint}}</div>
                            <div class="col s12 m4 l4">售出可獲unit point: </div>
                            <div class="cols12"></div>
                            <div class="col s6 m2 l3">突破Bonus<div class="hide-on-med-and-down">Lv. <input id="limitOverLv" type="number" min="1" max="{{$limit_over_max}}" value="1"></div></div>
                            <div class="col s6 m2 hide-on-large-only">Lv. <input id="limitOverLv2" type="number" min="1" max="{{$limit_over_max}}" value="1"></div>
                            <div class="col s6 m2 l3">HP: HPx<span id="limitOverHp"></span>%</div>
                            <div class="col s6 m2 l6">ATK: ATKx<span id="limitOverAtk"></span>%</div>
                            <div class="col s6 m2 l3">Cost: +<span id="limitOverCost"></span></div>
                            <div class="col s6 m2 l6">Charm: <span id="limitOverCharm"></span></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col s12 l6">
            <div class="hoverable">
                <div class="card-title grey white-text">
                    使用此素材進化的Unit
                </div>
                <div class="card-content black white-text">
                    @if(sizeof($evos) > 0)
                        <div class="row" style="margin-bottom: 0;">
                            <div class="col s12">
                                @foreach($evos as $part)
                                    <?=$imageUtil->getIcon(1, $part->partPre(), 50)?>
                                @endforeach
                            </div>
                        </div>
                    @else
                        無
                    @endif
                </div>
            </div>
        </div>
        <div class="col s12 l6">
            <div class="hoverable">
                <div class="card-title grey white-text">
                    進化
                </div>
                <div class="card-content black white-text">
                    <div class="section">進化自:&nbsp
                        @if(isset($evoFrom))
                            <br/><?=$imageUtil->getIcon(1, $evoFrom->partPre(), 50)?>&nbsp<i class="fa fa-plus fa-2x evo-icon" aria-hidden="true"></i>&nbsp
                            @for ($i = 1; $i < 5; $i++)
                                @if($evoFrom->part($i)->fix_id !== 0)
                                    <?=$imageUtil->getIcon(1, $evoFrom->part($i), 50)?>
                                @endif
                            @endfor
                            <i class="fa fa-arrow-right fa-2x evo-icon" aria-hidden="true"></i>&nbsp<?=$imageUtil->getIcon(1, $evoFrom->partAfter(), 50)?>
                        @else
                            無
                        @endif
                    </div>
                    <div class="divider"></div>
                    <div class="section">可進化至:&nbsp
                        @if(!is_null($evoTo))
                            <br/><?=$imageUtil->getIcon(1, $evoTo->partPre(), 50)?>&nbsp<i class="fa fa-plus fa-2x evo-icon" aria-hidden="true"></i>&nbsp
                            @for ($i = 1; $i < 5; $i++)
                                @if($evoTo->part($i)->fix_id !== 0)
                                    <?=$imageUtil->getIcon(1, $evoTo->part($i), 50)?>
                                @endif
                            @endfor
                            &nbsp<i class="fa fa-arrow-right fa-2x evo-icon" aria-hidden="true"></i>&nbsp<?=$imageUtil->getIcon(1, $evoTo->partAfter(), 50)?>
                            </div>
                            <div class="divider"></div>
                            <div class="section">朋友等級: Lv.{{$evoTo->friend_level}}或以上&nbsp&nbsp&nbsp&nbsp屬性: {{$function->getKind($evoTo->friend_kind)}}&nbsp&nbsp&nbsp&nbsp種族: <?=$imageUtil->getElement($evoTo->friend_elem, 18)?>&nbsp&nbsp&nbsp&nbsp進化關卡: <a href="{{action('PagesController@quest', $evoTo->quest()->fix_id)}}">{{$evoTo->quest()->quest_name}}</a>
                            @if ($evol_unitpoint > 0)
                                <br/>可使用 <img src="{{URL::asset('/img/icon/unit_point.png')}}" style="width:15px;height:15px;" alt="Unit Point">{{$evol_unitpoint}}Unit point進化
                            @endif
                        @else
                            無
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col s12">
            <div class="hoverable">
                <div class="card-title grey white-text">
                    入手方法 <small>β</samll>
                </div>
                <div class="card-content black white-text">
                    @if(sizeof($areas) > 0)
                        @foreach($areas as $area)
                            <div class="btn transparent"><a href="{{action('PagesController@area', $area->fix_id)}}">{{$area->area_name}}</a></div>
                        @endforeach
                    @else
                        無
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div id="translateModal" class="modal">
        <div class="modal-content">
            <h4 class="newTranslate">提供翻譯</h4>
            <h4 class="updateTranslate">更新翻譯</h4>
            <form id="translateForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col s12">
                        <h5>特殊格式</h5>
                        <dl>
                            <dt>Unit連結</dt>
                            <dd>[[Unit ID|文字]] (例. [[1925|光聖人]] 會被轉換成<a href="{{URL::asset('/')}}unit/1925">光聖人</a>)</dd>
                        </dl>
                    </div>
                    <div class="input-field col s12">
                        <textarea id="detailcn" class="materialize-textarea" name="detailcn"></textarea>
                        <label for="detailcn">中文翻譯</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a id="translateFormSubmit" class="teal white-text modal-action modal-close waves-effect waves-green btn-flat"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</a>
            <a id="translateFormClose" class="modal-action modal-close waves-effect waves-green btn-flat"><i class="fa fa-times" aria-hidden="true"></i> Close</a>
        </div>
    </div>
</div>
@stop
