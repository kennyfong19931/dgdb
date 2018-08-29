@extends('nav')

@section('title')DG點心紙 - Divine Gate 資料庫@stop

@section('social_netowrk')<meta property="og:title" content="DG點心紙"/><meta property="og:description" content="標記擁有的Unit"/><meta property="og:locale" content="zh_HK"><meta property="og:url" content="{{ URL::asset('/') }}"/><meta property="og:site_name" content="Divine Gate 資料庫" /><meta property="fb:app_id" content="1140845152610532" /><meta property="og:image" content="{{ URL::asset('/img/favicon.png') }}" />@stop

@section('style')#pre{display: none;}.allsw{padding: 10px;}#url{width: 90%;overflow: hidden;}@stop

@section('header')@if($type == 'collabo') <script src="{{ URL::asset('/js/collabo.js') }}"></script>@else<script src="{{ URL::asset('/js/rare.js') }}"></script>@endif <script src="{{ URL::asset('/js/mark_sheet.js') }}"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.12/clipboard.min.js"></script>@stop

@section('script')new Clipboard('#clipboardCopy');if(window.location.hash.length > 0){updateFromHash(window.location.hash.substring(1));}else{var name=cname+"_sheet=";var ca=document.cookie.split(';'); for(var i=0; i < ca.length; i++){var c=ca[i]; while (c.charAt(0)==' '){c=c.substring(1);}if (c.indexOf(name)==0){updateFromHash(c.substring(name.length, c.length));}}}@stop

@section('content')<div class="container"> <div class="row"> <div class="col s12"> <div class="allsw"><a class="waves-effect waves-light btn blue imageput">取得圖片</a><a class="waves-effect waves-light btn green allon">全部選擇</a><a class="waves-effect waves-light btn red alloff">全部清除</a>&nbsp;<a class="waves-effect waves-light btn orange" href="{{action('PagesController@mark', ['type'=> 'rare'])}}">課金抽棋</a><a class="waves-effect waves-light btn orange" href="{{action('PagesController@mark', ['type'=> 'collabo'])}}">合作抽棋</a></div></div><blockquote class="col s12"><button id="clipboardCopy" class="btn secondary-content tooltipped right" data-clipboard-target="#url" data-position="bottom" data-delay="50" data-tooltip="Copy to Clipboard"><i class="fa fa-clipboard" aria-hidden="true"></i></button><div id="url" class="white-text"></div></blockquote> <div class="col s12"><canvas id="list"></canvas><canvas id="pre"></canvas> </div></div></div>@stop
