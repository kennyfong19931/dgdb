<?php
    $imageUtil = new \App\Util\ImageUtil;
?>
@extends('nav')

@section('title')
Unit 列表 - Divine Gate 資料庫
@stop

@section('social_netowrk')
<meta property="og:title" content="Unit 列表"/>
<meta property="og:description" content="Divine Gate 所有Unit列表"/>
<meta property="og:locale" content="zh_HK">
<meta property="og:url" content="{{ URL::asset('/') }}"/>
<meta property="og:site_name" content="Divine Gate 資料庫" />
<meta property="fb:app_id" content="1140845152610532" />
<meta property="og:image" content="{{ URL::asset('/img/favicon.png') }}" />
@stop

@section('header')
<script type="text/javascript" src="https://npmcdn.com/isotope-layout@3.0/dist/isotope.pkgd.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
@stop

@section('style')
.unit {
  transform:translate3d(0,0,0);
  -webkit-transform:translate3d(0,0,0);
  -moz-transform:translate3d(0,0,0);
}
@stop

@section('script')
    $("img").lazyload({
        effect: "fadeIn",
        skip_invisible: true,
        failure_limit: Math.max($("img.lazy").length - 1, 0)
    });

	// init filter, prevent lag
	var filters = {series:'.再醒'};
	var filterValue = '';
	for ( var prop in filters ) {
		filterValue += filters[ prop ];
	}
	// init Isotope
    var $container = $('.isotope').isotope({
        itemSelector: '.unit',
		filter: filterValue
    });

    $container.on('layoutComplete', function(){
        $(window).trigger("scroll");
    });

	$('#filters').on( 'click', '.btn', function() {
		var $this = $(this);
		// get group key
		var $buttonGroup = $this.parents('.btn-group');
		var filterGroup = $buttonGroup.attr('data-filter-group');
		// update checked state
		$buttonGroup.find('.is-checked').removeClass('is-checked');
		$( this ).addClass('is-checked');
		$(this).addClass("active").siblings().removeClass("active");
		// set filter for group
		filters[ filterGroup ] = $this.attr('data-filter');
		// combine filters
		var filterValue = '';
		for ( var prop in filters ) {
			filterValue += filters[ prop ];
		}
		// set filter for Isotope
		$container.isotope({ filter: filterValue });
	});

    function utf8_to_b64(str) { return window.btoa(unescape(encodeURIComponent(str))); }
    function b64_to_utf8(str) { return decodeURIComponent(escape(window.atob(str))); }

    if(window.location.hash.length > 0){
        var filter = window.location.hash.substring(1);
        if(filter.length > 0){
            filter = b64_to_utf8(filter);
            $selected_button = $('.btn[data-filter*="'+filter+'"]');
            $selected_button.trigger( "click" );
        }
    }
@stop

@section('content')
<div class="container">
    <div id="filters">
        <div class="row">
            <div class="col s2 m1 white-text">稀有度</div>
            <div class="col s10 m11"><div class="btn-group js-radio-button-group" data-filter-group="rare">
                <button type="button" class="btn unitlist waves-effect waves-light white black-text active" data-filter="">全</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".rare0">★1</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".rare1">★2</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".rare2">★3</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".rare3">★4</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".rare4">★5</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".rare5">★6</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".rare6">★7</button>
            </div></div>
            <div class="col s2 m1 white-text">屬性</div>
            <div class="col s10 m11"><div class="btn-group js-radio-button-group" data-filter-group="elem">
                <button type="button" class="btn unitlist waves-effect waves-light white black-text active" data-filter="">全</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".elem2">炎</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".elem3">水</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".elem6">風</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".elem4">光</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".elem5">暗</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".elem1">無</button>
            </div></div>
            <div class="col s2 m1 white-text">種族</div>
            <div class="col s10 m11"><div class="btn-group js-radio-button-group" data-filter-group="kind">
                <button type="button" class="btn unitlist waves-effect waves-light white black-text active" data-filter="">全</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".kind1">人</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".kind2">龍</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".kind3">神</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".kind4">魔物</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".kind5">妖精</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".kind6">獸</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".kind7">機械</button>
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".kind8">強化合成</button>
            </div></div>
            <div class="col s2 m1 white-text">副種族</div>
                <div class="col s10 m11"><div class="btn-group js-radio-button-group" data-filter-group="subkind">
                    <button type="button" class="btn unitlist waves-effect waves-light white black-text active" data-filter="">全</button>
                    <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".subkind1">人</button>
                    <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".subkind2">龍</button>
                    <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".subkind3">神</button>
                    <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".subkind4">魔物</button>
                    <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".subkind5">妖精</button>
                    <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".subkind6">獸</button>
                    <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".subkind7">機械</button>
                    <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".subkind8">強化合成</button>
            </div></div>
            <div class="col s2 m1 white-text">Link</div>
                <div class="col s10 m11"><div class="btn-group js-radio-button-group" data-filter-group="subkind">
                    <button type="button" class="btn unitlist waves-effect waves-light white black-text active" data-filter="">全</button>
                    <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter=".hvlink">可Link</button>
            </div></div>
            <div class="col s2 m1 white-text">系列</div>
            <div class="col s10 m11"><div class="btn-group js-radio-button-group" data-filter-group="series">
                <button type="button" class="btn unitlist waves-effect waves-light white black-text" data-filter="">全</button>
                <button type="button" class="btn unitlist waves-effect waves-light orange" data-filter=".降臨">降臨</button>
                <button type="button" class="btn unitlist waves-effect waves-light orange" data-filter=".亂入">亂入</button>
                <button type="button" class="btn unitlist waves-effect waves-light orange" data-filter=".限定">限定</button>
                <button type="button" class="btn unitlist waves-effect waves-light orange active" data-filter=".再醒">再醒</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".主角">主角</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".刃龍">刃龍</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".元素">元素</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".波克魯">波克魯</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".貓">貓</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".第三世代">第三世代</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".刑者">刑者</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".乙女">乙女</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".轉色獸">轉色獸</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".進化素材">進化素材</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".蛋">蛋</button></button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".第四世代">第四世代</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".神">神</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".萬聖節">萬聖節</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".童話">童話</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".花之妖精">花之妖精</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".聖誕節">聖誕節</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".四次元">四次元</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".浴室美女">浴室美女</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".情人節">情人節</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".文明龍">文明龍</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".白色情人節">白色情人節</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".番人">番人</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".狐嫁">狐嫁</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".墮聖之結婚式">墮聖之結婚式</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".花獸">花獸</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".七夕">七夕</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".子彈">子彈</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".樂奏龍">樂奏龍</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".病神">病神</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".中秋節">中秋節</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".失工場">失工場</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".保護區">保護區</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".賞櫻">賞櫻</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".聖門學園">聖門學園</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".升技素材">升技素材</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".EarlyDays">Early Days</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".Link素材">Link 素材</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".災害對策部">災害對策部</button>
                <button type="button" class="btn unitlist waves-effect waves-light" data-filter=".梅塔波">梅塔波</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".機械龍">機械龍</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".精靈王">精靈王</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".圓桌騎士">圓桌騎士</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".天才">天才</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".機械娘">機械娘</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".綠野仙蹤">綠野仙蹤</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".拘束獸">拘束獸</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".特務龍">特務龍</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".六魔將">六魔將</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".聖劇之戲曲">聖劇之戲曲</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".北歐神">北歐神</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".六波羅">六波羅</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".六神通">六神通</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".調聖者">調聖者</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".新世界評議會">新世界評議會</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".數字">數字</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".天氣術師">天氣術師</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".畫伯">畫伯</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".神獸者">神獸者</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".水著">水著</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".雙子">雙子</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".三藝神">三藝神</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".BD2016">BD2016</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".裏古龍眾">裏古龍眾</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".創醒之巫女">創醒之巫女</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".魔獸士">魔獸士</button>
                <button type="button" class="btn unitlist waves-effect waves-light red" data-filter=".刻命神">刻命神</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".命運石之門">命運石之門</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".妖精的尾巴">妖精的尾巴</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".進擊的巨人">進擊的巨人</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".公主踢">公主踢</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".魔法禁書目錄">魔法禁書目錄</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".Appli-Style">Appli-Style</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".Psycho-Pass">Psycho-Pass</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".約會大作戰">約會大作戰</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".WEGO">WEGO</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".初音未來">初音未來</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".GungHo">GungHo</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".魔法少女小圓">魔法少女小圓</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".無頭騎士異聞錄">無頭騎士異聞錄</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".IS">Infinite Stratos</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".FSN">Fate/stay night</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".征龍之路">征龍之路</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".NGNL">No Game No Life</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".魔法科高中的劣等生">魔法科高中的劣等生</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".黑色子彈">黑色子彈</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".偽戀">偽戀</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".槍彈辯駁">槍彈辯駁</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".攻殻">攻殻機動隊</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".噬血狂襲">噬血狂襲</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".EVA">新世紀福音戰士</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".好想大聲說出心底的話">好想大聲說出心底的話</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".鋼之鍊金術師">鋼之鍊金術師</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".七大罪">七大罪</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".重裝武器">重裝武器</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".斬！赤紅之瞳">斬！赤紅之瞳</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".灼眼的夏娜">灼眼的夏娜</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".血界戰線">血界戰線</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".死神">死神</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".阿松">阿松</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".Re0">Re：從零開始的異世界生活</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".VV">Village Vanguard</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".魔奇少年">魔奇少年</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".果青">果然我的青春戀愛喜劇搞錯了。</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".魔伊">魔法少女☆伊莉雅3rei</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".午睡公主">午睡公主</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".美好世界">為世界獻上美好的祝福2</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".點兔">請問您今天要來點兔子嗎？？</button>
                <button type="button" class="btn unitlist waves-effect waves-light blue" data-filter=".劍姬神聖譚">在地下城尋求邂逅是否搞錯了什麼 外傳 劍姬神聖譚</button>
            </div></div>
        </div>
    </div>
    <div class="row">
        <div class="isotope col s12">
            @foreach($unitlist as $unit)
                <?=$imageUtil->getIcon(2, $unit, 50)?>
            @endforeach
        </div>
    </div>
</div>
@stop
