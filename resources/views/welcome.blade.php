<?php
    $function = new \App\Util\FunctionUtil;
    $imageUtil = new \App\Util\ImageUtil;
    use Carbon\Carbon;
    $today = Carbon::now();
    $hour = $today->hour-1;
    if($hour === -1)
        $hour = 23;

    $new_units = array();
    $update_units = array();
?>
@extends('nav')

@section('title')
Divine Gate 資料庫
@stop

@section('social_netowrk')
<meta property="og:title" content="Divine Gate 資料庫"/>
<meta property="og:description" content="Divine Gate 資料庫"/>
<meta property="og:locale" content="zh_HK">
<meta property="og:url" content="{{ URL::asset('/') }}"/>
<meta property="og:site_name" content="Divine Gate 資料庫" />
<meta property="fb:app_id" content="1140845152610532" />
<meta property="og:image" content="{{ URL::asset('/img/favicon.png') }}" />
@stop

@section('script')
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
        if (val !== '' && val.length > 1) {
          $.each(data, function(key, unit){
              if (data.hasOwnProperty(key) &&
                  unit.name.toLowerCase().indexOf(val) !== -1 &&
                  unit.name.toLowerCase() !== val) {
                var autocompleteOption = $('<li value="'+ unit.value +'"></li>');
                autocompleteOption.append('<img src="'+ unit.img +'" class="right circle"><span>'+ unit.name +'</span>');
                $autocomplete.append(autocompleteOption);

                highlight(val, autocompleteOption);
              }
          });
        }
      });

      // Set input value
      $autocomplete.on('click', 'li', function () {
        $input.val($(this).text());
        $autocomplete.empty();
        location.href = '{{ URL::asset('/') }}unit/'+$(this).val();
      });
    }
  });
};
$('input.autocomplete').autocomplete2({
    data: [
    @foreach($unitlist as $unit)
        {name: "{{$function->getTriId($unit->draw_id)}} - {{$unit->name}}", value: "{{$unit->draw_id}}", img: "{{$unit->image}}"},
    @endforeach
    ]
});
@stop

@section('style')
th, td{
    text-align: center;
}
.fb_iframe_widget{
    width: 100%;
}
@stop

@section('content')
<div id="fb-root"></div>
<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.7";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="container">
    <div class="row">
        <div class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <input type="text" id="autocomplete-unit" class="autocomplete">
                    <label for="autocomplete-input">快速尋找Unit (eg. 001 - アカネ)</label>
                </div>
            </div>
        </div>
        <div class="col s12 m6 l4 right">
            @if(sizeof($new_units) > 0)
                <div class="hoverable">
                    <div class="card-title blue white-text">
                        最近新增Unit
                    </div>
                    <div class="card-content grey darken-4 white-text">
                        @foreach($new_units as $unit)
                            <?=$imageUtil->getIcon(1, $unit, 50)?>
                        @endforeach
                    </div>
                </div>
            @endif
            @if(sizeof($update_units) > 0)
                <div class="hoverable">
                    <div class="card-title orange white-text">
                        最近調整Unit
                    </div>
                    <div class="card-content grey darken-4 white-text">
                        @foreach($update_units as $unit)
                            <?=$imageUtil->getIcon(1, $unit, 50)?>
                        @endforeach
                    </div>
                </div>
            @endif
            <div class="fb-page" data-href="https://www.facebook.com/divinegatedb/" data-tabs="timeline" data-width="500" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false"><blockquote cite="https://www.facebook.com/divinegatedb/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/divinegatedb/">Divine Gate 資料庫</a></blockquote></div>
        </div>
        <div class="col s12 m6 l4">
            <div class="hoverable">
                <div class="card-title red white-text">
                    緊急時間表
                </div>
                <div class="card-content grey darken-4 white-text">
                    @foreach($egg as $type => $typeArr)
                        <table>
                            <thead><tr><th colspan="{{sizeof($typeArr)}}"><?=$imageUtil->DIcon($typeArr['area'],$typeArr['boss'])?></th></tr></thead>
                            <tbody><tr><td>{{$today->format('m月d日')}}</td>
                                @foreach($typeArr as $key => $val)
                                    @if(is_int($key))
                                        @if($val == $hour)
                                            <td class="primary">{{$val}}時</td>
                                        @else
                                            <td>{{$val}}時</td>
                                        @endif
                                    @endif
                                @endforeach
                            </tr></tbody>
                        </table>
                        <div class="divider"></div>
                    @endforeach
                    <table>
                        @foreach($material as $quest => $materials)
                            <tr><td><?=$imageUtil->materialIcon($quest)?></td>
                            @foreach($materials as $time)
                                @if($time == $hour)
                                    <td class="primary">{{$time}}時</td>
                                @else
                                    <td>{{$time}}時</td>
                                @endif
                            @endforeach
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col s12 m6 l4">
            <div class="hoverable">
                <div class="card-title green white-text">
                    活動時間表
                </div>
                <div class="card-content grey darken-4 white-text">
                    <table>
                        <thead><tr><th>時間</th><th>關卡</th></tr></thead>
                        <tbody>
                            @if(isset($events['today']))
                                @foreach($events['today'] as $event)
                                    <?php
                                        $time_start = Carbon::createFromFormat('YmdH', $event[0]->timing_start)->subHour()->format('m月d日H時');
                                        $time_end = Carbon::createFromFormat('YmdH', $event[0]->timing_end)->subHour()->format('m月d日H時');
                                    ?>
                                    <tr><td>{{$time_start}} ~ <br/>{{$time_end}}</td><td><?=$imageUtil->DIcon($event[1],$event[2])?></td></tr>
                                @endforeach
                            @endif
                            @if(isset($events['future']))
                                <tr><td colspan="2">
                                    <ul class="collapsible" data-collapsible="accordion"><li>
                                        <div class="collapsible-header light-green"><i class="fa fa-calendar" aria-hidden="true"></i>未來活動</div>
                                        <div class="collapsible-body"><table>
                                            @foreach($events['future'] as $event)
                                                <?php
                                                    $time_start = Carbon::createFromFormat('YmdH', $event[0]->timing_start)->subHour()->format('m月d日H時');
                                                    $time_end = Carbon::createFromFormat('YmdH', $event[0]->timing_end)->subHour()->format('m月d日H時');
                                                ?>
                                                <tr><td>{{$time_start}} ~ <br/>{{$time_end}}</td><td><?=$imageUtil->DIcon($event[1],$event[2])?></td></tr>
                                            @endforeach
                                        </table></div>
                                    </li></ul>
                                </td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
