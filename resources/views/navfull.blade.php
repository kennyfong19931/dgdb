<?php header("Content-Type:text/html; charset=utf-8"); ?>
<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#">
    <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <title>@yield('title')</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google" content="notranslate" />
        <meta name="keywords" content="Divine Gate, 資料庫, db, dgdb, database, Divine Gate 维基">
        <meta name="description" content="Divine Gate is a game developed by GungHo Online Entertainment">
        <link rel="shortcut icon" href="{{ URL::asset('/img/favicon.ico') }}" type="image/x-icon">
        <link rel="icon" href="{{ URL::asset('/img/favicon.ico') }}" type="image/x-icon">

        <!-- Browser theme color: https://developers.google.com/web/fundamentals/design-and-ui/browser-customization/theme-color?hl=en -->
        <meta name="theme-color" content="#330033">
        <meta name="msapplication-navbutton-color" content="#330033">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <link rel="apple-touch-startup-image" href="{{ URL::asset('/img/favicon.png') }}">
        <link rel="apple-touch-icon" href="{{ URL::asset('/img/favicon.png') }}">

        <!-- CSS/JS -->
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
    	<link href='http://fonts.googleapis.com/earlyaccess/notosanstc.css' rel='stylesheet' type='text/css'>
        <link href='{{ URL::asset('/css/site.css') }}' rel='stylesheet' type='text/css'>
        <style type="text/css">
            @yield('style')
    	</style>

        @yield('header')

        <!-- social network related -->
        <meta name="twitter:card" content="summary" />
        @yield('social_netowrk')
    </head>
    <body class="grey darken-3">
        <header>
            <ul id="dropdownSkill" class="dropdown-content">
                @if(Request::is('skill/l'))
                    <li class="active"><a href="{{action('PagesController@skill', 'l')}}">隊長技能 Leader Skill</a></li>
                @else
                    <li><a href="{{action('PagesController@skill', 'l')}}">隊長技能 Leader Skill</a></li>
                @endif
                @if(Request::is('skill/a'))
                    <li class="active"><a href="{{action('PagesController@skill', 'a')}}">主動技能 Active Skill</a></li>
                @else
                    <li><a href="{{action('PagesController@skill', 'a')}}">主動技能 Active Skill</a></li>
                @endif
                @if(Request::is('skill/n'))
                    <li class="active"><a href="{{action('PagesController@skill', 'n')}}">普通技能 Normal Skill</a></li>
                @else
                    <li><a href="{{action('PagesController@skill', 'n')}}">普通技能 Normal Skill</a></li>
                @endif
                @if(Request::is('skill/p'))
                    <li class="active"><a href="{{action('PagesController@skill', 'p')}}">被動技能 Passive Skill</a></li>
                @else
                    <li><a href="{{action('PagesController@skill', 'p')}}">被動技能 Passive Skill</a></li>
                @endif
                @if(Request::is('skill/ln'))
                    <li class="active"><a href="{{action('PagesController@skill', 'ln')}}">Link技能 Link Skill</a></li>
                @else
                    <li><a href="{{action('PagesController@skill', 'ln')}}">Link技能 Link Skill</a></li>
                @endif
                @if(Request::is('skill/lp'))
                    <li class="active"><a href="{{action('PagesController@skill', 'lp')}}">Link被動技能 Link Passive</a></li>
                @else
                    <li><a href="{{action('PagesController@skill', 'lp')}}">Link被動技能 Link Passive</a></li>
                @endif
            </ul>
            <ul id="dropdownLink" class="dropdown-content">
                <li><a href="http://www.gungho.jp/dg/" target="_blank">【官方】Gungho Divine Gate</a></li>
                <li><a href="http://mobile.gungho.jp/news/dg/unei.html" target="_blank">【官方】營運消息</a></li>
                <li><a href="https://twitter.com/divine_gate" target="_blank">【官方】Twitter</a></li>
                <li><a href="http://www.youtube.com/channel/UCEJ35J7jFA2s8TXRmlVAkGg" target="_blank">【官方】Youtube</a></li>
                <li><a href="http://www.marv.jp/special/divinegate/" target="_blank">【官方】動畫官網</a></li>
                <li class="divider"></li>
                <li><a href="https://www.facebook.com/divinegatepage" target="_blank">【Facebook】Divine Gate</a></li>
                <li><a href="http://forum.gamer.com.tw/B.php?bsn=24865" target="_blank">【巴哈姆特】Divine Gate討論版</a></li>
                <li><a href="http://tieba.baidu.com/f?ie=utf-8&kw=%E7%A5%9E%E5%9C%A3%E4%B9%8B%E9%97%A8" target="_blank">【百度貼吧】神聖之門吧</a></li>
                <li><a href="http://dg.pad-plus.com/" target="_blank">【攻略&速報】ディバゲまとめぷらす</a></li>
                <li><a href="http://dg.koga.me/" target="_blank">【攻略】ディバゲDB</a></li>
                <li><a href="http://divine-gate.net/" target="_blank">【資料庫】Divine-Gate.net</a></li>
                <li><a href="http://games.gaym.jp/iPhone/divinegate/" target="_blank">【日本Wiki】Gaym</a></li>
            </ul>
            <div class="navbar-fixed">
                <nav class="grey darken-4">
                    <div class="nav-wrapper container">
                        <a href="{{ url('/') }}" class="brand-logo">Divine Gate 資料庫</a>
                        <ul class="right hide-on-med-and-down">
                            @if(Request::is('unitlist'))
                                <li class="active"><a href="{{action('PagesController@unitlist')}}">Unit 列表</a></li>
                            @else
                                <li><a href="{{action('PagesController@unitlist')}}">Unit 列表</a></li>
                            @endif
                            @if(Request::is('area/*') || Request::is('quest/*') || Request::is('questlist'))
                                <li class="active"><a href="{{action('PagesController@questlist')}}">地下城資料</a></li>
                            @else
                                <li><a href="{{action('PagesController@questlist')}}">地下城資料</a></li>
                            @endif
                            @if(Request::is('skill/*'))
                                <li class="active"><a class="dropdown-button-wide" data-beloworigin="true" data-constrainwidth="false" data-activates="dropdownSkill">技能一覽表<i class="fa fa-caret-down" aria-hidden="true"></i></a></li>
                            @else
                                <li><a class="dropdown-button-wide" data-beloworigin="true" data-constrainwidth="false" data-activates="dropdownSkill">技能一覽表<i class="fa fa-caret-down" aria-hidden="true"></i></a></li>
                            @endif
                            @if(Request::is('mark/*'))
                                <li class="active"><a href="{{action('PagesController@mark', ['type' => 'rare'])}}">DG點心紙</a></li>
                            @else
                                <li><a href="{{action('PagesController@mark', ['type' => 'rare'])}}">DG點心紙</a></li>
                            @endif
                            @if(Request::is('voteresult'))
                                <li class="active"><a href="{{action('PagesController@voteResult')}}">人氣投票結果</a></li>
                            @else
                                <li><a href="{{action('PagesController@voteResult')}}">人氣投票結果</a></li>
                            @endif
                            <li><a class="dropdown-button-wide" data-beloworigin="true" data-constrainwidth="false" data-activates="dropdownLink">推薦連結<i class="fa fa-caret-down" aria-hidden="true"></i></a></li>
                        </ul>
                        <ul id="slide-out" class="side-nav">
                            @if(Request::is('unitlist'))
                                <li class="active"><a href="{{action('PagesController@unitlist')}}">Unit 列表</a></li>
                            @else
                                <li><a href="{{action('PagesController@unitlist')}}">Unit 列表</a></li>
                            @endif
                            @if(Request::is('area/*') || Request::is('quest/*') || Request::is('questlist'))
                                <li class="active"><a href="{{action('PagesController@questlist')}}">地下城資料</a></li>
                            @else
                                <li><a href="{{action('PagesController@questlist')}}">地下城資料</a></li>
                            @endif
                            @if(Request::is('skill/*'))
                                <li class="active"><a class="dropdown-button" data-beloworigin="true" data-activates="dropdownSkill">技能一覽表<i class="fa fa-caret-down" aria-hidden="true"></i></a></li>
                            @else
                                <li><a class="dropdown-button" data-beloworigin="true" data-activates="dropdownSkill">技能一覽表<i class="fa fa-caret-down" aria-hidden="true"></i></a></li>
                            @endif
                            @if(Request::is('mark/*'))
                                <li class="active"><a href="{{action('PagesController@mark', ['type' => 'rare'])}}">DG點心紙</a></li>
                            @else
                                <li><a href="{{action('PagesController@mark', ['type' => 'rare'])}}">DG點心紙</a></li>
                            @endif
                            @if(Request::is('voteresult'))
                                <li class="active"><a href="{{action('PagesController@voteResult')}}">人氣投票結果</a></li>
                            @else
                                <li><a href="{{action('PagesController@voteResult')}}">人氣投票結果</a></li>
                            @endif
                            <li><a class="dropdown-button" data-beloworigin="true" data-activates="dropdownLink">推薦連結<i class="fa fa-caret-down" aria-hidden="true"></i></a></li>
                        </ul>
                        <div class="container">
                            <a href="#" data-activates="slide-out" class="button-collapse"><i class="fa fa-bars" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        <main>
			<script>
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

				ga('create', 'UA-52412001-1', 'auto');
				ga('send', 'pageview');
			</script>
            <a class="btn-floating btn-large waves-effect waves-light red scroll-to-top"><i class="fa fa-angle-up" aria-hidden="true"></i>></a>
            @yield('content')
        </main>
        <footer class="page-footer">
            <div class="container">
                <div class="row">
                    <div class="col s11">Graphics © Copyright by Gungho Online Entertainment, Inc.</div>
                    <div class="col s1"><a class="grey-text text-lighten-4 right" href="https://www.facebook.com/divinegatedb/"><i class="fa fa-facebook-official fa-2x" aria-hidden="true"></i></a></div>
                </div>
            </div>
        </footer>
        <div id="fb-root"></div>
        <script>
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.7&appId=1140845152610532";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
            $( document ).ready(function() {
                if ( $(window).width() > 992) {
                    $(".dropdown-button-wide").dropdown();
                } else {
                    // Initialize side nav
                    $(".button-collapse").sideNav();
                    $('.collapsible').collapsible();
                }
                $('.scroll-to-top').click(function(e) {
                    e.preventDefault();
                    $('html, body').animate({scrollTop : 0}, 300);
                });
                $(window).on('scroll', function(){
                    if ($(this).scrollTop() > 100) {
                        $('.scroll-to-top').stop().fadeTo('fast',1);
                    } else {
                        $('.scroll-to-top').stop().fadeTo('fast',0);
                    }
                });
                @yield('script')
            });
        </script>
    </body>
</html>
