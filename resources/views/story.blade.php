<?php
    $function = new \App\Util\FunctionUtil;
    $imageUtil = new \App\Util\ImageUtil;
?>
@extends('nav')

@section('title')
故事列表 - Divine Gate 資料庫
@stop

@section('social_netowrk')
<meta property="og:title" content="故事列表"/>
<meta property="og:description" content="Divine Gate 故事列表"/>
<meta property="og:locale" content="zh_HK">
<meta property="og:url" content="{{ URL::asset('/') }}"/>
<meta property="og:site_name" content="Divine Gate 資料庫" />
<meta property="fb:app_id" content="1140845152610532" />
<meta property="og:image" content="{{ URL::asset('/img/favicon.png') }}" />
@stop

@section('script')
$( "li" ).each(function( index ) {
    $(this).html($(this).html().replace(/\[\[([0-9]+)\|([^\]]+)\]\]/g, '<a href="{{URL::asset('/')}}unit/$1">$2</a>' ));;
});

/*************************************
*        Auto complete plugin        *
* extract from materializecss source *
*************************************/
$.fn.autocomplete2 = function (options) {
  // Defaults
  var defaults = {
      data: []
  };

options = $.extend(defaults, options);

return this.each(function() {
    var $input = $(this);
    var data = options.data,
    minLength = options.minLength,
    $inputDiv = $input.closest('.input-field'); // Div to append on

    // Check if data isn't empty
    if (!$.isEmptyObject(data)) {
      // Create autocomplete element
      var $autocomplete = $('<ul class="autocomplete-content dropdown-content"></ul>');

      // Append autocomplete element
      if ($inputDiv.length) {
        $inputDiv.append($autocomplete); // Set ul in body
      } else {
        $input.after($autocomplete);
      }

      var highlight = function(string, $el) {
        var img = $el.find('img');
        var matchStart = $el.text().toLowerCase().indexOf("" + string.toLowerCase() + ""),
            matchEnd = matchStart + string.length - 1,
            beforeMatch = $el.text().slice(0, matchStart),
            matchText = $el.text().slice(matchStart, matchEnd + 1),
            afterMatch = $el.text().slice(matchEnd + 1);
        $el.html("<span>" + beforeMatch + "<span class='highlight'>" + matchText + "</span>" + afterMatch + "</span>");
        if (img.length) {
          $el.prepend(img);
        }
      };

      // Perform search
      $input.on('keyup', function (e) {
        // Capture Enter
        if (e.which === 13) {
          $autocomplete.find('li').first().click();
          return;
        }

        var val = $input.val().toLowerCase();
        $autocomplete.empty();

        // Check if the input isn't empty
        if (val !== '' && val.length > minLength) {
          $.each(data, function(key, unit){
              if (data.hasOwnProperty(key) &&
                  unit.name.toLowerCase().indexOf(val) !== -1 &&
                  unit.name.toLowerCase() !== val) {
                var autocompleteOption = $('<li value="'+ unit.value +'"></li>');
                if(unit.hasOwnProperty('image'))
                    autocompleteOption.append('<img src="'+ unit.image +'" class="right circle"><span>'+ unit.name +'</span>');
                else
                    autocompleteOption.append('<span>'+ unit.name +'</span>');
                $autocomplete.append(autocompleteOption);

                highlight(val, autocompleteOption);
              }
          });
        }
      });

      // Set input value
      $autocomplete.on('click', 'li', function () {
        $autocomplete.empty();
        $('.chips').addChip($chips.data('index'), {tag: $(this).text()}, $chips);
      });
    }
  });
};
$('.chips').material_chip();
$('.chips').on('chip.add', function(e, chip){
    $(chip).find("material-icons").removeClass("material-icons close").addClass("fa fa-times");
});
$('input.autocomplete').autocomplete2({
    minLength: 0,
    data: [
        {name: "降臨系列", value:"降臨", tag:"降臨"},
        {name: "亂入系列", value:"亂入", tag:"亂入"},
        {name: "限定系列", value:"限定", tag:"限定"},
        {name: "再醒系列", value:"再醒", tag:"再醒"},
        {name: "主角系列", value:"主角", tag:"主角"},
        {name: "刃龍系列", value:"刃龍", tag:"刃龍"},
        {name: "元素系列", value:"元素", tag:"元素"},
        {name: "波克魯系列", value:"波克魯", tag:"波克魯"},
        {name: "貓系列", value:"貓", tag:"貓"},
        {name: "第三世代系列", value:"第三世代", tag:"第三世代"},
        {name: "刑者系列", value:"刑者", tag:"刑者"},
        {name: "乙女系列", value:"乙女", tag:"乙女"},
        {name: "轉色獸系列", value:"轉色獸", tag:"轉色獸"},
        {name: "進化素材系列", value:"進化素材", tag:"進化素材"},
        {name: "蛋系列", value:"蛋", tag:"蛋"},
        {name: "第四世代系列", value:"第四世代", tag:"第四世代"},
        {name: "神系列", value:"神", tag:"神"},
        {name: "萬聖節系列", value:"萬聖節", tag:"萬聖節"},
        {name: "童話系列", value:"童話", tag:"童話"},
        {name: "花之妖精系列", value:"花之妖精", tag:"花之妖精"},
        {name: "聖誕節系列", value:"聖誕節", tag:"聖誕節"},
        {name: "四次元系列", value:"四次元", tag:"四次元"},
        {name: "浴室美女系列", value:"浴室美女", tag:"浴室美女"},
        {name: "情人節系列", value:"情人節", tag:"情人節"},
        {name: "文明龍系列", value:"文明龍", tag:"文明龍"},
        {name: "白色情人節系列", value:"白色情人節", tag:"白色情人節"},
        {name: "番人系列", value:"番人", tag:"番人"},
        {name: "狐嫁系列", value:"狐嫁", tag:"狐嫁"},
        {name: "墮聖之結婚式系列", value:"墮聖之結婚式", tag:"墮聖之結婚式"},
        {name: "花獸系列", value:"花獸", tag:"花獸"},
        {name: "七夕系列", value:"七夕", tag:"七夕"},
        {name: "子彈系列", value:"子彈", tag:"子彈"},
        {name: "樂奏龍系列", value:"樂奏龍", tag:"樂奏龍"},
        {name: "病神系列", value:"病神", tag:"病神"},
        {name: "中秋節系列", value:"中秋節", tag:"中秋節"},
        {name: "失工場系列", value:"失工場", tag:"失工場"},
        {name: "保護區系列", value:"保護區", tag:"保護區"},
        {name: "賞櫻系列", value:"賞櫻", tag:"賞櫻"},
        {name: "聖門學園系列", value:"聖門學園", tag:"聖門學園"},
        {name: "升技素材系列", value:"升技素材", tag:"升技素材"},
        {name: "Early Days系列", value:"EarlyDays", tag:"Early Days"},
        {name: "Link 素材系列", value:"Link素材", tag:"Link素材"},
        {name: "災害對策部系列", value:"災害對策部", tag:"災害對策部"},
        {name: "梅塔波系列", value:"梅塔波", tag:"梅塔波"},
        {name: "機械龍系列", value:"機械龍", tag:"機械龍"},
        {name: "精靈王系列", value:"精靈王", tag:"精靈王"},
        {name: "圓桌騎士系列", value:"圓桌騎士", tag:"圓桌騎士"},
        {name: "天才系列", value:"天才", tag:"天才"},
        {name: "機械娘系列", value:"機械娘", tag:"機械娘"},
        {name: "綠野仙蹤系列", value:"綠野仙蹤", tag:"綠野仙蹤"},
        {name: "拘束獸系列", value:"拘束獸", tag:"拘束獸"},
        {name: "特務龍系列", value:"特務龍", tag:"特務龍"},
        {name: "六魔將系列", value:"六魔將", tag:"六魔將"},
        {name: "聖劇之戲曲系列", value:"聖劇之戲曲", tag:"聖劇之戲曲"},
        {name: "北歐神系列", value:"北歐神", tag:"北歐神"},
        {name: "六波羅系列", value:"六波羅", tag:"六波羅"},
        {name: "六神通系列", value:"六神通", tag:"六神通"},
        {name: "調聖者系列", value:"調聖者", tag:"調聖者"},
        {name: "新世界評議會系列", value:"新世界評議會", tag:"新世界評議會"},
        {name: "數字系列", value:"數字", tag:"數字"},
        {name: "天氣術師系列", value:"天氣術師", tag:"天氣術師"},
        {name: "畫伯系列", value:"畫伯", tag:"畫伯"},
        {name: "神獸者系列", value:"神獸者", tag:"神獸者"},
        {name: "水著系列", value:"水著", tag:"水著"},
        {name: "雙子系列", value:"雙子", tag:"雙子"},
        {name: "三藝神系列", value:"三藝神", tag:"三藝神"},
        {name: "BD2016系列", value:"BD2016", tag:"BD2016"},
        {name: "裏古龍眾系列", value:"裏古龍眾", tag:"裏古龍眾"},
        {name: "創醒之巫女系列", value:"創醒之巫女", tag:"創醒之巫女"},
        {name: "命運石之門系列", value:"命運石之門", tag:"命運石之門"},
        {name: "妖精的尾巴系列", value:"妖精的尾巴", tag:"妖精的尾巴"},
        {name: "進擊的巨人系列", value:"進擊的巨人", tag:"進擊的巨人"},
        {name: "公主踢系列", value:"公主踢", tag:"公主踢"},
        {name: "魔法禁書目錄系列", value:"魔法禁書目錄", tag:"魔法禁書目錄"},
        {name: "Appli-Style系列", value:"Appli-Style", tag:"Appli-Style"},
        {name: "Psycho-Pass系列", value:"Psycho-Pass", tag:"Psycho-Pass"},
        {name: "約會大作戰系列", value:"約會大作戰", tag:"約會大作戰"},
        {name: "WEGO系列", value:"WEGO", tag:"WEGO"},
        {name: "初音未來系列", value:"初音未來", tag:"初音未來"},
        {name: "GungHo系列", value:"GungHo", tag:"GungHo"},
        {name: "魔法少女小圓系列", value:"魔法少女小圓", tag:"魔法少女小圓"},
        {name: "無頭騎士異聞錄系列", value:"無頭騎士異聞錄", tag:"無頭騎士異聞錄"},
        {name: "Infinite Stratos系列", value:"IS", tag:"IS"},
        {name: "Fate/stay night系列", value:"FSN", tag:"FSN"},
        {name: "征龍之路系列", value:"征龍之路", tag:"征龍之路"},
        {name: "No Game No Life系列", value:"NGNL", tag:"NGNL"},
        {name: "魔法科高中的劣等生系列", value:"魔法科高中的劣等生", tag:"魔法科高中的劣等生"},
        {name: "黑色子彈系列", value:"黑色子彈", tag:"黑色子彈"},
        {name: "偽戀系列", value:"偽戀", tag:"偽戀"},
        {name: "槍彈辯駁系列", value:"槍彈辯駁", tag:"槍彈辯駁"},
        {name: "攻殻機動隊系列", value:"攻殻", tag:"攻殻"},
        {name: "噬血狂襲系列", value:"噬血狂襲", tag:"噬血狂襲"},
        {name: "新世紀福音戰士系列", value:"EVA", tag:"EVA"},
        {name: "好想大聲說出心底的話系列", value:"好想大聲說出心底的話", tag:"好想大聲說出心底的話"},
        {name: "鋼之鍊金術師系列", value:"鋼之鍊金術師", tag:"鋼之鍊金術師"},
        {name: "七大罪系列", value:"七大罪", tag:"七大罪"},
        {name: "重裝武器系列", value:"重裝武器", tag:"重裝武器"},
        {name: "斬！赤紅之瞳系列", value:"斬！赤紅之瞳", tag:"斬！赤紅之瞳"},
        {name: "灼眼的夏娜系列", value:"灼眼的夏娜", tag:"灼眼的夏娜"},
        {name: "血界戰線系列", value:"血界戰線", tag:"血界戰線"},
        {name: "死神系列", value:"死神", tag:"死神"},
        {name: "阿松系列", value:"阿松", tag:"阿松"},
        {name: "Re：從零開始的異世界生活系列", value:"Re0", tag:"Re：從零開始的異世界生活"},
        {name: "Village Vanguard系列", value:"VV", tag:"Village Vanguard"},
    @foreach($unitlist as $unit)
        {name: "{{$function->getTriId($unit->draw_id)}} - {{$unit->name}}", value: "{{$unit->draw_id}}", image: "<?=$imageUtil->getIconLink($function->getTriId($unit->draw_id))?>", tag: "{{$unit->name}}"},
    @endforeach
    ]
});
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="input-field col s12">
            <input type="text" id="autocomplete-unit" class="autocomplete">
            <label for="autocomplete-input">快速尋找Unit (eg. 001 - アカネ)</label>
        </div>
        <div class="col s12">
            <div class="chips"></div>
            <ul id="translation" class="white-text">
            </ul>
        </div>
    </div>
</div>
@stop
