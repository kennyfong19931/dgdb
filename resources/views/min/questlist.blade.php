@extends('nav')

@section('title')地下城列表 - Divine Gate 資料庫@stop

@section('social_netowrk')<meta property="og:title" content="地下城列表"/><meta property="og:description" content="Divine Gate 全地下城列表"/><meta property="og:locale" content="zh_HK"><meta property="og:url" content="{{ URL::asset('/') }}"/><meta property="og:site_name" content="Divine Gate 資料庫" /><meta property="fb:app_id" content="1140845152610532" /><meta property="og:image" content="{{ URL::asset('/img/favicon.png') }}" />@stop

@section('script')$(".card-content").each(function(i, obj){$(obj).html($(obj).html().replace(/\[([A-Fa-f0-9]{6}|w{3})\]/g,'<span style="color:#$1">'));$(obj).html($(obj).html().replace(/\[\-\]/g,'</span>'));});@stop

@section('content')<div class="container"> <div class="row"> <div class="col s12 m6 l3"> <?=printByType(1, $areaCate, $areaById)?> <?=printByType(6, $areaCate, $areaById)?> <?=printByType(7, $areaCate, $areaById)?> <?=printByType(3, $areaCate, $areaById)?> <?=printByType(5, $areaCate, $areaById)?> <?=printByType(10, $areaCate, $areaById)?> </div><div class="col s12 m6 l3"> <?=printByType(4, $areaCate, $areaById)?> <?=printByType(2, $areaCate, $areaById)?> </div><div class="col s12 m6 l3"> <?=printByType(9, $areaCate, $areaById)?> </div><div class="col s12 m6 l3"> <?=printByType(8, $areaCate, $areaById)?> </div></div></div>@stop

<?php
    function printByType($type, $areaCate, $areaById){
        $imageUtil = new \App\Util\ImageUtil;

        $cateType = $areaCate[$type];
        $returnStr = '<div class="hoverable">';
        switch($type){
            case 1:
                $returnStr .= '<div class="card-title primary white-text">普通地下城</div>';
                break;
            case 2:
                $returnStr .= '<div class="card-title indigo white-text">單/雙周地下城</div>';
                break;
            case 3:
                $returnStr .= '<div class="card-title red white-text">緊急地下城</div>';
                break;
            case 4:
                $returnStr .= '<div class="card-title teal white-text">降臨地下城</div>';
                break;
            case 5:
                $returnStr .= '<div class="card-title green white-text">活動地下城</div>';
                break;
            case 6:
                $returnStr .= '<div class="card-title amber white-text">進化神殿</div>';
                break;
            case 7:
                $returnStr .= '<div class="card-title grey white-text">無限迴廊</div>';
                break;
            case 8:
                $returnStr .= '<div class="card-title blue-grey white-text">銀幣地下城</div>';
                break;
            case 9:
                $returnStr .= '<div class="card-title light-green white-text">合作地下城</div>';
                break;
            case 10:
                $returnStr .= '<div class="card-title cyan white-text">鑰匙地下城</div>';
                break;
        }
        $returnStr .= '<div class="card-content black white-text">';
        foreach($cateType as $cate){
            $returnStr .= '<span class="card-title">'.$cate->area_cate_name.'</span>';
            if(isset($areaById[$cate->fix_id]) && sizeof($areaById[$cate->fix_id]) > 0){
                $returnStr .= '<ul>';
                foreach($areaById[$cate->fix_id] as $area){
                    $returnStr .= '<li>'.$imageUtil->DIcon($area[0],$area[1]).'</li>';
                }
                $returnStr .= '</ul>';
            }
        }
        $returnStr .= '</div></div>';
        return $returnStr;
    }
?>
