@extends('nav')

@section('style')
.row.collapsible-content{
    margin-left: 0rem;
    margin-right: 0rem;
}
dl{
    -webkit-margin-before: 0em;
    -webkit-margin-after: 0em;
}
dd, dt{
    display: block;float: left;
}
dt {
    clear: left;
    width: 45px;
    text-align: right;
}
dd{
    width: 80%;
    margin-bottom: 8px;
    -webkit-margin-start: 0px;
}
.tabs .indicator{
    background-color: #ba68c8;
}
@stop

@section('title')
人氣投票結果 - Divine Gate 資料庫
@stop

@section('social_netowrk')
<meta property="og:title" content="人氣投票結果"/>
<meta property="og:description" content="Divine Gate 所有人氣投票結果"/>
<meta property="og:locale" content="zh_HK">
<meta property="og:url" content="{{ URL::asset('/') }}"/>
<meta property="og:site_name" content="Divine Gate 資料庫" />
<meta property="fb:app_id" content="1140845152610532" />
<meta property="og:image" content="{{ URL::asset('/img/favicon.png') }}" />
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12 white-text">
            <h3>人氣投票結果</h3>
        </div>
        <div class="col s12">
            <ul class="collapsible popout" data-collapsible="accordion">
                <li>
                    <div class="collapsible-header indigo active">第十三回人氣投票</div>
                    <div class="collapsible-body black white-text">
                        <div class="row collapsible-content">
                            <div class="col s12">
                                <ul class="tabs">
                                    <li class="tab col s1 yellow"><a class="black-text" class="active" href="#13fav">最喜歡的角色</a></li>
                                    <li class="tab col s1 red"><a class="white-text" href="#13tsundere">嬌起來很厲害</a></li>
                                    <li class="tab col s1 blue"><a class="white-text" href="#13wife">會成為好伴侶</a></li>
                                    <li class="tab col s1 green"><a class="white-text" href="#13cook">料理很厲害</a></li>
                                </ul>
                            </div>
                            <div id="13fav" class="col s12"><?=printResult($vote13['fav'])?></div></div>
                            <div id="13tsundere" class="col s12"><?=printResult($vote13['tsundere'])?></div></div>
                            <div id="13wife" class="col s12"><?=printResult($vote13['wife'])?></div></div>
                            <div id="13cook" class="col s12"><?=printResult($vote13['cook'])?></div></div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header blue">第十二回人氣投票</div>
                    <div class="collapsible-body black white-text">
                        <div class="row collapsible-content">
                            <?=printResult($vote12)?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header green">第十一回人氣投票</div>
                    <div class="collapsible-body black white-text">
                        <div class="row collapsible-content">
                            <?=printResult($vote11)?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header yellow">第十回人氣投票</div>
                    <div class="collapsible-body black white-text">
                        <div class="row collapsible-content">
                            <div class="col s12"><div class="card-panel blue lighten-4 blue-text text-darken-3">是次Unit排名是集計的，把各種進化、節日限定、衍生版本的票數合併計算</div></div>
                            <?=printResult($vote10)?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header orange">第九回人氣投票</div>
                    <div class="collapsible-body black white-text">
                        <div class="row collapsible-content">
                            <div class="col s12"><div class="card-panel orange white-text">排名為<a class="white-text" href="http://dengekionline.com/elem/000/001/117/1117158/">電撃App 戦う！美少女キャラ総選挙 2015夏</a>的結果</div></div>
                            <?=printResult($vote9)?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header red">第八回人氣投票</div>
                    <div class="collapsible-body black white-text">
                        <div class="row collapsible-content">
                            <div class="col s12">
                                <ul class="tabs">
                                    <li class="tab col s1"><a class="black-text" class="active" href="#8all">總合</a></li>
                                    <li class="tab col s1 red"><a class="white-text" href="#8fire">炎屬性</a></li>
                                    <li class="tab col s1 blue"><a class="white-text" href="#8water">水屬性</a></li>
                                    <li class="tab col s1 green"><a class="white-text" href="#8wind">風屬性</a></li>
                                    <li class="tab col s1 yellow"><a class="black-text" href="#8light">光屬性</a></li>
                                    <li class="tab col s1 purple"><a class="white-text" href="#8dark">暗屬性</a></li>
                                    <li class="tab col s1"><a class="black-text" href="#8none">無屬性</a></li>
                                </ul>
                            </div>
                            <div id="8all" class="col s12"><?=printResult($vote8['all'])?></div></div>
                            <div id="8fire" class="col s12"><?=printResult($vote8['fire'])?></div></div>
                            <div id="8water" class="col s12"><?=printResult($vote8['water'])?></div></div>
                            <div id="8wind" class="col s12"><?=printResult($vote8['wind'])?></div></div>
                            <div id="8light" class="col s12"><?=printResult($vote8['light'])?></div>
                            <div id="8dark" class="col s12"><?=printResult($vote8['dark'])?></div></div>
                            <div id="8none" class="col s12"><?=printResult($vote8['none'])?></div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header purple">第七回人氣投票</div>
                    <div class="collapsible-body black white-text">
                        <div class="row collapsible-content">
                            <?=printResult($vote7)?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header indigo">第六回人氣投票</div>
                    <div class="collapsible-body black white-text">
                        <div class="row collapsible-content">
                            <?=printResult($vote6)?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header blue">第五回人氣投票</div>
                    <div class="collapsible-body black white-text">
                        <div class="row collapsible-content">
                            <?=printResult($vote5)?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header green">第四回人氣投票</div>
                    <div class="collapsible-body black white-text">
                        <div class="row collapsible-content">
                            <?=printResult($vote4)?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header yellow">第三回人氣投票</div>
                    <div class="collapsible-body black white-text">
                        <div class="row collapsible-content">
                            <?=printResult($vote3)?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header orange">第二回人氣投票</div>
                    <div class="collapsible-body black white-text">
                        <div class="row collapsible-content">
                            <?=printResult($vote2)?>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header red">第一回人氣投票</div>
                    <div class="collapsible-body black white-text">
                        <div class="row collapsible-content">
                            <?=printResult($vote1)?>
                        </div>
                    </div>
            </ul>
        </div>
    </div>
</div>
@stop
<?php
    function printResult($units){
        $imageUtil = new \App\Util\ImageUtil;
        $returnStr = '';
        $unitCol = ceil((sizeof($units)-3)/3);
        $count = 1;
        foreach($units as $unit){
            if($unit['rank'] == '1' || $unit['rank'] == '2' || $unit['rank'] == '3'){
                if(isset($unit['end'])){
                    if(!$unit['end']){
                        $returnStr .= '<div class="col s4"><big>';
                        if($unit['rank'] == '1')
                            $returnStr .= '第一名';
                        elseif($unit['rank'] == '2')
                            $returnStr .= '第二名';
                        elseif($unit['rank'] == '3')
                            $returnStr .= '第三名';
                    }
                } else {
                    $returnStr .= '<div class="col s4"><big>';
                    if($unit['rank'] == '1')
                        $returnStr .= '第一名';
                    elseif($unit['rank'] == '2')
                        $returnStr .= '第二名';
                    elseif($unit['rank'] == '3')
                        $returnStr .= '第三名';
                }
                $returnStr .= '</big>';
                if(isset($unit['type']) && isset($unit['change']))
                    $returnStr .= getRankChange($unit['type'],$unit['change']);
                $returnStr .= '<br/>'.$imageUtil->getIcon(3, $unit['unit'], $unit['rank']);
                if(isset($unit['end'])){
                    if($unit['end']){
                        $returnStr .= '</div>';
                        if($unit['rank'] == '3')
                            $returnStr .= '<div class="col s12 divider"></div>';
                    }
                } else {
                    $returnStr .= '</div>';
                    if($unit['rank'] == '3')
                        $returnStr .= '<div class="col s12 divider"></div>';
                }
            }else{
                if($count == 1){
                    $returnStr .= '<div class="col s12 m4"><dl>';
                }
                $returnStr .= '<dt>';
                if(isset($unit['type']) && isset($unit['change']))
                    $returnStr .= getRankChange($unit['type'],$unit['change']);
                $returnStr .= ' '.$imageUtil->getIcon(3, $unit['unit'], $unit['rank']).'</dd>';
                if($count == $unitCol){
                    $returnStr .= '</dl></div>';
                    $count = 1;
                }else{
                    $count++;
                }
            }
        }
        return $returnStr;
    }

    function getRankChange($type, $change = 0){
        if($type == 'up')
            return '<span class="green-text">↑'.$change.'</span>';
        elseif($type == 'down')
            return '<span class="red-text">↓'.$change.'</span>';
        elseif($type == 'equal')
            return '<span class="yellow-text">=</span>';
        elseif($type == 'new')
            return '<span class="orange-text">新</span>';
    }
?>
