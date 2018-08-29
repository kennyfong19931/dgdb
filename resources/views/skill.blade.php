@extends('nav')

@section('title')
@if($type == "n")
普通技能一覽表 - Divine Gate 資料庫
@elseif($type == "l")
隊長技能一覽表 - Divine Gate 資料庫
@elseif($type == "a")
主動技能一覽表 - Divine Gate 資料庫
@elseif($type == "p")
被動技能一覽表 - Divine Gate 資料庫
@elseif($type == "ln")
Link技能一覽表 - Divine Gate 資料庫
@elseif($type == "lp")
Link被動技能一覽表 - Divine Gate 資料庫
@endif
@stop

@section('social_netowrk')
@if($type == "n")
<meta property="og:title" content="普通技能一覽表"/>
<meta property="og:description" content="Divine Gate 所有普通技能列表"/>
@elseif($type == "l")
<meta property="og:title" content="隊長技能一覽表"/>
<meta property="og:description" content="Divine Gate 所有隊長技能列表"/>
@elseif($type == "a")
<meta property="og:title" content="主動技能一覽表"/>
<meta property="og:description" content="Divine Gate 所有主動技能列表"/>
@elseif($type == "p")
<meta property="og:title" content="被動技能一覽表"/>
<meta property="og:description" content="Divine Gate 所有被動技能列表"/>
@elseif($type == "ln")
<meta property="og:title" content="Link技能一覽表"/>
<meta property="og:description" content="Divine Gate 所有Link技能列表"/>
@elseif($type == "lp")
<meta property="og:title" content="Link被動技能一覽表"/>
<meta property="og:description" content="Divine Gate 所有Link被動技能列表"/>
@endif
<meta property="og:locale" content="zh_HK">
<meta property="og:url" content="{{ URL::asset('/') }}"/>
<meta property="og:site_name" content="Divine Gate 資料庫" />
<meta property="fb:app_id" content="1140845152610532" />
<meta property="og:image" content="{{ URL::asset('/img/favicon.png') }}" />
@stop

@section('header')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.13/css/dataTables.material.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.min.css">
<link href="{{ URL::asset('/css/chosen-material-theme.min.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('script')
    $("h4").each(function() {
        $('#toc').append("<li><a href='#" + $(this).attr("id") + "'>" + $(this).text() +"</a></li>");
    });

    /* multi table table filter */
    $('table').DataTable({"paging":false,"info":false,"ordering":false,"language": {"zeroRecords": "暫無技能"},
        @if ($type == 'n')
        "columnDefs": [
            {
                "targets": [ 3 ],
                "visible": false
            }
        ],
        @endif
        "columns": [
            { "data": "name" },
            { "data": null,
                render: function (data, type, row) {
                    var details = row.detail + "<br/>" + row.detailCn;
                    details = details.replace(/\[([A-Fa-f0-9]{6}|w{3})\]/g,'<span style="color:#$1">');
                    details = details.replace(/\[\-\]/g,'</span>');
                    return details;
                }
            },
            @if ($type == 'n')
            { "data": "card",
                render: function (data, type, row){
                    var details = "";
                    for(i in data){
                        switch(data[i]){
                			case 1:
                				details += '<img src="http://img.qov.tw/icon/none.jpg" style="height:25px;width:25px;" alt="無"></img>';
                				break;
                			case 2:
                				details += '<img src="http://img.qov.tw/icon/fire.jpg" style="height:25px;width:25px;" alt="炎"></img>';
                				break;
                			case 3:
                				details += '<img src="http://img.qov.tw/icon/water.jpg" style="height:25px;width:25px;" alt="水"></img>';
                				break;
                			case 4:
                				details += '<img src="http://img.qov.tw/icon/light.jpg" style="height:25px;width:25px;" alt="光"></img>';
                				break;
                			case 5:
                				details += '<img src="http://img.qov.tw/icon/dark.jpg" style="height:25px;width:25px;" alt="暗"></img>';
                				break;
                			case 6:
                				details += '<img src="http://img.qov.tw/icon/wind.jpg" style="height:25px;width:25px;" alt="風"></img>';
                				break;
                			case 7:
                				details += '<img src="http://img.qov.tw/icon/life.jpg" style="height:25px;width:25px;" alt="心"></img>';
                				break;
                        }
                    }
                    return details;
                }
            },
            { "data": "cardDesc" },
            @endif
            { "data": "units",
                render: function (data, type, row) {
                    var details = "";
                    for(i in data){
                        details += '<span class="left tooltipped" data-position="bottom" data-delay="50" data-tooltip="No.'+data[i].draw_id+' - '+data[i].name+'"><a href={{action('PagesController@index')}}/unit/'+data[i].draw_id+'><img class="lazy unit-valign" src="http://img.qov.tw/empty.png" data-original="'+data[i].image+'" height="50px" width="50px" alt="No.'+data[i].draw_id+'"/></a></span>';
                    }
                    return details;
                }
            }
        ]});
    $("#filter").chosen({width: "100%",search_contains:true,single_backstroke_delete:true,allow_single_deselect:true,no_results_text:"不支援此過濾詞"})
    .on('change', function(e, param){
        if($("#filter").val()!=null){
            $('table').DataTable().search($("#filter").val().join(" ")).draw();
        }else{
            $('table').DataTable().search("").draw();
        }
        $(window).trigger("scroll");
    });
    Materialize.toast('<i class="fa fa-spin fa-spinner" aria-hidden="true"></i>&nbsp&nbsp正在載入技能資料', 60000, 'light-blue');
    $.ajax({
        url: "{{ action('ApiController@skill', ['type' => $type]) }}",
        type: "GET",
        dataType: "json",
        success: function(data) {
            $('.toast').remove();
            if(data.status === 1){
                for(i in data.skill){
                    $('#table'+i).DataTable().rows.add(data.skill[i]).draw();
                }
            } else {
                Materialize.toast('<i class="fa fa-exclamation" aria-hidden="true"></i>&nbsp&nbsp發生錯誤，無法獲取技能資料', 4000, 'red');
            }
            $('.tooltipped').tooltip({delay: 50});
            $("img").lazyload({
                effect: "fadeIn",
                skip_invisible: true,
                failure_limit: Math.max($("img.lazy").length - 1, 0)
            });
            $('.scrollspy').scrollSpy();
        },
        error: function() {
            $('.toast').remove();
            Materialize.toast('<i class="fa fa-exclamation" aria-hidden="true"></i>&nbsp&nbsp發生錯誤，無法獲取技能資料', 4000, 'red');
        }
    });

    $('#toc').pushpin({ top: $('#nav').height(), offset: $('nav').height() });
@stop

@section('style')
.dataTables_filter { display: none; }
@stop

@section('content')
<div class="row">
	<div class="col l2 hide-on-med-and-down">
        <ul id="toc" class="section table-of-contents"></ul>
	</div>
    <div class="col s12 l10" style="margin-top: 10px;">
        <select multiple id="filter" data-placeholder="過濾技能">
			<optgroup label="屬性">
				<option value="炎属性">炎</option>
				<option value="水属性">水</option>
				<option value="風属性">風</option>
				<option value="光属性">光</option>
				<option value="闇属性">暗</option>
				<option value="無属性">無</option>
			</optgroup>
            @if ($type == 'n' || $type == 'ln')
			<optgroup label="攻擊">
				<option value="小">小</option>
				<option value="中">中</option>
				<option value="大">大</option>
				<option value="特大">特大</option>
				<option value="超特大">超特大</option>
				<option value="絶大">絕大</option>
				<option value="超絶大">超絕大</option>
				<option value="極大">極大</option>
				<option value="貫通">貫通</option>
			</optgroup>
            @if ($type == 'n')
			<optgroup label="卡牌組合">
                <option value="單色">單色</option>
                <option value="雜色">雜色</option>
                <option value="1板">1板</option>
                <option value="2板">2板</option>
                <option value="3板">3板</option>
                <option value="4板">4板</option>
                <option value="5板">5板</option>
                <option value="炎板">炎板</option>
                <option value="水板">水板</option>
                <option value="風板">風板</option>
                <option value="光板">光板</option>
                <option value="暗板">暗板</option>
                <option value="無板">無板</option>
            </optgroup>
			<optgroup label="附加">
				<option value="BOOST">BOOST</option>
				<option value="特性">特性</option>
			</optgroup>
            @endif
            @endif
		</select>
    </div>
    @if($type == "n" || $type == "ln")
    <div class="col s12 l10 white-text right">
        @if($type == "p")
            <h2>普通技能 <small>Normal Skill</small></h2>
        @elseif($type == "lp")
            <h2>Link普通技能 <small>Link Normal Skill</small></h2>
        @endif
        <h4 id="1" class="section scrollspy">單體攻擊</h4>
        <?=printTable(1, $type);?>
		<h4 id="2" class="section scrollspy">全體攻擊</h4>
		<?=printTable(2, $type);?>
		<h4 id="3" class="section scrollspy">回復</h4>
		<?=printTable(3, $type);?>
    </div>
    @elseif($type == "l")
    <div class="col s12 l10 white-text right">
        <h2>隊長技能 <small>Leader Skill</small></h2>
        <h4 id="1" class="section scrollspy">攻擊力倍率</h4>
        <?=printTable(1, $type);?>
		<h4 id="2" class="section scrollspy">血量倍率</h4>
        <?=printTable(2, $type);?>
		<h4 id="3" class="section scrollspy">攻擊力及血量倍率</h4>
        <?=printTable(3, $type);?>
		<h4 id="4" class="section scrollspy">追打</h4>
        <?=printTable(4, $type);?>
		<h4 id="5" class="section scrollspy">傷害減免</h4>
        <?=printTable(5, $type);?>
		<h4 id="6" class="section scrollspy">回合回血</h4>
        <?=printTable(6, $type);?>
		<h4 id="7" class="section scrollspy">攻擊時間增加</h4>
        <?=printTable(7, $type);?>
		<h4 id="8" class="section scrollspy">回復上升</h4>
        <?=printTable(8, $type);?>
		<h4 id="9" class="section scrollspy">攻擊回血</h4>
        <?=printTable(9, $type);?>
		<h4 id="10" class="section scrollspy">戰鬥回血</h4>
        <?=printTable(10, $type);?>
		<h4 id="11" class="section scrollspy">按隊伍血量條件提升</h4>
        <?=printTable(11, $type);?>
		<h4 id="12" class="section scrollspy">按combo達成條件提升</h4>
        <?=printTable(12, $type);?>
		<h4 id="13" class="section scrollspy">根性</h4>
        <?=printTable(13, $type);?>
		<h4 id="14" class="section scrollspy">降低所受傷害</h4>
        <?=printTable(14, $type);?>
		<h4 id="15" class="section scrollspy">攻擊順序</h4>
        <?=printTable(15, $type);?>
		<h4 id="16" class="section scrollspy">轉換技能</h4>
        <?=printTable(16, $type);?>
		<h4 id="17" class="section scrollspy">按指定攻擊條件提升</h4>
        <?=printTable(17, $type);?>
		<h4 id="18" class="section scrollspy">通關獲得經驗值增加</h4>
        <?=printTable(18, $type);?>
		<h4 id="19" class="section scrollspy">按敵人降低所受傷害</h4>
        <?=printTable(19, $type);?>
    </div>
    @elseif($type == "a")
    <div class="col s12 l10 white-text right">
        <h2>主動技能 <small>Active Skill</small></h2>
        <h4 id="1" class="section scrollspy">倍數攻擊</h4>
        <?=printTable(1, $type);?>
		<h4 id="2" class="section scrollspy">攻擊後回復</h4>
        <?=printTable(2, $type);?>
		<h4 id="3" class="section scrollspy">屬性攻擊</h4>
        <?=printTable(3, $type);?>
		<h4 id="4" class="section scrollspy">犧牲攻擊</h4>
        <?=printTable(4, $type);?>
		<h4 id="5" class="section scrollspy">機率攻擊</h4>
        <?=printTable(5, $type);?>
		<h4 id="6" class="section scrollspy">固定攻擊</h4>
        <?=printTable(6, $type);?>
		<h4 id="7" class="section scrollspy">屬性固定攻擊</h4>
        <?=printTable(7, $type);?>
		<h4 id="8" class="section scrollspy">特殊攻擊</h4>
        <?=printTable(8, $type);?>
		<h4 id="9" class="section scrollspy">中毒</h4>
        <?=printTable(9, $type);?>
		<h4 id="10" class="section scrollspy">防禦破壞</h4>
        <?=printTable(10, $type);?>
		<h4 id="11" class="section scrollspy">拖延</h4>
        <?=printTable(11, $type);?>
		<h4 id="12" class="section scrollspy">防禦上升</h4>
        <?=printTable(12, $type);?>
		<h4 id="13" class="section scrollspy">攻擊力強化</h4>
        <?=printTable(13, $type);?>
		<h4 id="14" class="section scrollspy">時間延長</h4>
        <?=printTable(14, $type);?>
		<h4 id="15" class="section scrollspy">屬性攻擊無效</h4>
        <?=printTable(15, $type);?>
		<h4 id="16" class="section scrollspy">反擊</h4>
        <?=printTable(16, $type);?>
		<h4 id="17" class="section scrollspy">卡板轉換</h4>
        <?=printTable(17, $type);?>
		<h4 id="18" class="section scrollspy">回復</h4>
        <?=printTable(18, $type);?>
		<h4 id="19" class="section scrollspy">HP 回復</h4>
        <?=printTable(19, $type);?>
		<h4 id="20" class="section scrollspy">SP 回復</h4>
        <?=printTable(20, $type);?>
		<h4 id="21" class="section scrollspy">CD減少</h4>
        <?=printTable(21, $type);?>
		<h4 id="22" class="section scrollspy">傳送</h4>
        <?=printTable(22, $type);?>
		<h4 id="23" class="section scrollspy">直接打開所有？格</h4>
        <?=printTable(23, $type);?>
		<h4 id="24" class="section scrollspy">血量回復, 消除異常狀態</h4>
        <?=printTable(24, $type);?>
		<h4 id="25" class="section scrollspy">放板格設置卡板</h4>
        <?=printTable(25, $type);?>
    </div>
    @elseif($type == "p" || $type == "lp")
    <div class="col s12 l10 white-text right">
        @if($type == "p")
            <h2>被動技能 <small>Passive Skill</small></h2>
        @elseif($type == "lp")
            <h2>Link被動技能 <small>Link Passive Skill</small></h2>
        @endif
        <h4 id="1" class="section scrollspy">陷阱免疫</h4>
        <?=printTable(1, $type);?>
		<h4 id="2" class="section scrollspy">按敵人種族提升</h4>
        <?=printTable(2, $type);?>
		<h4 id="3" class="section scrollspy">反擊</h4>
        <?=printTable(3, $type);?>
		<h4 id="4" class="section scrollspy">回復</h4>
        <?=printTable(4, $type);?>
		<h4 id="5" class="section scrollspy">按隊伍血量條件提升</h4>
        <?=printTable(5, $type);?>
		<h4 id="6" class="section scrollspy">不會發生Back attack</h4>
        <?=printTable(6, $type);?>
		<h4 id="7" class="section scrollspy">防禦</h4>
        <?=printTable(7, $type);?>
		<h4 id="8" class="section scrollspy">BOOST格出現率更變</h4>
        <?=printTable(8, $type);?>
		<h4 id="9" class="section scrollspy">Panel出現率更變</h4>
        <?=printTable(9, $type);?>
		<h4 id="10" class="section scrollspy">按手持的板減傷</h4>
        <?=printTable(10, $type);?>
		<h4 id="11" class="section scrollspy">按攻擊Combo回復</h4>
        <?=printTable(11, $type);?>
		<h4 id="12" class="section scrollspy">攻擊時間增加</h4>
        <?=printTable(12, $type);?>
		<h4 id="13" class="section scrollspy">按Combo增加Rate</h4>
        <?=printTable(13, $type);?>
		<h4 id="14" class="section scrollspy">根性</h4>
        <?=printTable(14, $type);?>
		<h4 id="15" class="section scrollspy">移動時回復血量</h4>
        <?=printTable(15, $type);?>
		<h4 id="16" class="section scrollspy">戰鬥時回復血量</h4>
        <?=printTable(16, $type);?>
		<h4 id="17" class="section scrollspy">追打</h4>
        <?=printTable(17, $type);?>
		<h4 id="18" class="section scrollspy">翻開地板後影響攻擊力</h4>
        <?=printTable(18, $type);?>
		<h4 id="19" class="section scrollspy">多種屬性同時攻擊時、攻擊力上升</h4>
        <?=printTable(19, $type);?>
		<h4 id="20" class="section scrollspy">按Combo增加攻擊力</h4>
        <?=printTable(20, $type);?>
    </div>
    @endif
</div>
@stop
<?php
    function printTable($num, $type){
        /*$imageUtil = new \App\Util\ImageUtil;
        $returnStr = '<div class="row">';
        if($type == 'n' || $type == 'ln')
            $returnStr .= '<div class="col s2 strong">技能名稱</div><div class="col s4 strong">技能內容</div><div class="col s2 l1 strong">卡牌組合</div><div class="col s4 l5 strong">所持 Unit</div><div class="col s12 top-border"></div>';
        else
            $returnStr .= '<div class="col s2 strong">技能名稱</div><div class="col s5 strong">技能內容</div><div class="col s5 strong">所持 Unit</div><div class="col s12 top-border"></div>';
        if(!isset($skills[$num]))
            $returnStr .= '<div class="col s12">暫無</div>';
        else {
            $count = 0;
            foreach($skills[$num] as $skill){
                if($type == 'ln' || $type == 'lp')
                    $units = $skill->linkUnits();
                else
                    $units = $skill->units();
                if(sizeof($units) > 0){
                    if($type == 'n' || $type == 'ln'){
                        $returnStr .= '<div class="col s2">'.$skill->name.'</div><div class="col s4 detail">'.$skill->detail.'<br/>'.$skill->getDetailCn().'</div><div class="col s2 l1">';
                        $card = $skill->getCard();
                        for($i = 4; $i >= 0; $i--){
                            if($card[$i] != 0)
                                $returnStr .= '<div class="left">'.$imageUtil->getElement($card[$i],25).'</div>';
                        }
                        $returnStr .= '</div><div class="col s4 l5">';
                        foreach($units as $unit){ $returnStr .= $imageUtil->getIcon(1, $unit, 50); }
                        $returnStr .= '</div><div class="col s12 divider"></div>';
                    } else {
                        $returnStr .= '<div class="col s2">'.$skill->name.'</div><div class="col s5">'.$skill->detail.'<br/>'.$skill->getDetailCn().'</div><div class="col s5">';
                        foreach($units as $unit){ $returnStr .= $imageUtil->getIcon(1, $unit, 50); }
                        $returnStr .= '</div><div class="col s12 divider"></div>';
                    }
                    $count++;
                }
            }
            if($count == 0)
                $returnStr .= '<div class="col s12">暫無</div>';
        }
        $returnStr .= '</div>';
        return $returnStr;*/
        $returnStr = '<table class="bordered" id="table'.$num.'">';
        if($type == 'n')
            $returnStr .= '<thead><tr><th style="width:16%">技能名稱</th><th style="width:33%">技能內容</th><th style="width:16%">卡牌組合</th><th>Card Description</th><th style="width:33%">所持 Unit</th></tr></thead></table>';
        else
            $returnStr .= '<thead><tr><th style="width:16%">技能名稱</th><th style="width:42%">技能內容</th><th style="width:42%">所持 Unit</th></tr></thead></table>';
        return $returnStr;
    }
?>
