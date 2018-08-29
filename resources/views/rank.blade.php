@extends('nav')

@section('title')
Rank能力一覽表 - Divine Gate 資料庫
@stop

@section('social_netowrk')
<meta property="og:title" content="Rank能力一覽表"/>
<meta property="og:description" content="查看各Rank的資料"/>
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
			<h3>Rank能力一覽表</h3>
			<table class="centered bordered">
				<thead><tr>
					<th data-field="rank">Rank</th>
					<th data-field="exp">升級經驗值</th>
					<th data-field="expTotal">累計經驗值</th>
					<th data-field="stamina">體力</th>
					<th data-field="friend">好友</th>
					<th data-field="backpack">背包</th>
					<th data-field="cost">Cost</th>
				</tr></thead>
				<tbody>
					@foreach($rank as $level)
					<tr>
						<td>{{$level->fix_id}}</td>
						<td>{{$level->exp_next}}</td>
						<td>{{$level->exp_next_total}}</td>
						<td>{{$level->stamina}}</td>
						<td>{{$level->friend_max}}</td>
						<td>{{$level->unit_max}}</td>
						<td>{{$level->party_cost}}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
	    </div>
	</div>
</div>
@stop
