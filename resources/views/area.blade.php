<?php
    $imageUtil = new \App\Util\ImageUtil;
    $title = preg_replace('/\[([A-Fa-f0-9]{6}|w{3})\]/','',$area->area_name);
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
<meta property="og:image" content="{{ URL::asset('/img/area/'.$area->res_map.'_upper.png') }}" />
<meta property="og:image" content="{{ URL::asset('/img/area/'.$area->res_map.'_lower.png') }}" />
<meta property="og:image" content="{{ URL::asset('/img/panel/'.$area->res_icon_key.'.png') }}" />
<meta property="og:image" content="{{ URL::asset('/img/panel/'.$area->res_icon_box.'.png') }}" />
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
.btn{
    overflow: hidden;
}
@stop

@section('script')
$(".quest-name").each(function(i, obj){
    $(obj).html($(obj).html().replace(/\[([A-Fa-f0-9]{6}|w{3})\]/g,'<span style="color:#$1">'));
    $(obj).html($(obj).html().replace(/\[\-\]/g,'</span>'));
});
@stop

@section('content')
    <div class="container">
        <div class="row">
            <nav class="primary">
                <div class="nav-wrapper">
                    <div class="col s12 breadcrumb-div">
                        <span class="breadcrumb">{{$area_cate_name}}</span>
                        <span class="breadcrumb">{{$area->area_name}}</span>
                    </div>
                </div>
            </nav>
            <img class="map" src="{{ URL::asset('/img/area/'.$area->res_map.'_upper.png') }}">
            <div class="area">
                <img class="map lower" src="{{ URL::asset('/img/area/'.$area->res_map.'_lower.png') }}">
                <div class="content col s12 row">
                    <div class="col s12 m5 l3">
                        <img src="{{ URL::asset('/img/panel/'.$area->res_icon_key.'.png') }}"><img src="{{ URL::asset('/img/panel/'.$area->res_icon_box.'.png') }}">
                        @if(strlen($area->area_url) > 0)
                            <br/><a class="btn" href="{{$area->area_url}}" target="_blank">官方活動頁面: {{$area_cate_name}}</a>
                        @endif
                    </div>
                    <div class="col s12 m7 l9">
                        <div class="card-title grey darken-1 white-text">
                            地下城中各色卡片出現機率
                        </div>
                        <div class="card-content">
                            <div class="chip hoverable red"><?=$imageUtil->getElement(2,32)?>{{$area->cost1*2}}%</div>
                            <div class="chip hoverable blue"><?=$imageUtil->getElement(3,32)?>{{$area->cost2*2}}%</div>
                            <div class="chip hoverable green"><?=$imageUtil->getElement(6,32)?>{{$area->cost3*2}}%</div>
                            <div class="chip hoverable yellow"><?=$imageUtil->getElement(4,32)?>{{$area->cost4*2}}%</div>
                            <div class="chip hoverable purple"><?=$imageUtil->getElement(5,32)?>{{$area->cost5*2}}%</div>
                            <div class="chip hoverable white"><?=$imageUtil->getElement(1,32)?>{{$area->cost0*2}}%</div>
                            <div class="chip hoverable pink lighten-2"><?=$imageUtil->getElement(7,32)?>{{$area->cost6*2}}%</div>
                        </div>
                    </div>
                    <div class="col s12"></div>
                    <div class="col s5 m6 grey">
                        地下城
                    </div>
                    <div class="col s5 m4 grey">
                        所需體力 / 券 / 鑰匙
                    </div>
                    <div class="col s2 m2 grey">
                        層數
                    </div>
                    @foreach($quests as $quest)
                      <div class="col s7 m6 quest-name"><a href="{{ action('PagesController@quest', ['id' => $quest->fix_id]) }}">{{$quest->quest_name}}</a></div>
                      <div class="col s3 m4 grey-text">{{$quest->quest_stamina}} / {{$quest->quest_ticket}} / {{$quest->quest_key}}</div>
                      <div class="col s2 m2 grey-text">{{$quest->floor_count}}</div>
                      <div class="col s12"></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop
