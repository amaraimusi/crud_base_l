<?php 
$ver_str = '?=1.0.0';
?>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="{{ asset('/js/app.js') }}" defer></script>
		<script src="{{ asset('/js/jquery-3.6.1.min.js') }}" defer></script>
		<script src="{{ asset('/js/LineDemo/FriendsList.js')  . $ver_str}} }}" defer></script>
		<script src="{{ asset('/js/LineDemo/friends_list.js')  . $ver_str}} }}" defer></script>
		
		<link href="{{ asset('/css/app.css')  . $ver_str}}" rel="stylesheet">
		<link href="{{ asset('/js/font/css/open-iconic.min.css') }}" rel="stylesheet">
		<link href="{{ asset('/css/common/style.css') }}" rel="stylesheet">
		<link href="{{ asset('/css/common/common.css')  . $ver_str}}" rel="stylesheet">
	
        <title>LINEチャネル:友だちユーザー一覧</title>
    </head>
    <body><div class="container-fluid">
    	
    	
    	<h2>LINE チャネル: 友だちユーザー一覧</h2>
		<div id="app"></div>

		<div id="err" class="text-danger"></div>
		<div id="res" class="text-success"></div>
		
		<div>
		
			<table id="form_tbl" class="table" style="max-width:1600px">
				<thead>
					<tr><th style="width:10%;">名称</th><th style="width:40%;">入力</th><th style="width:50%;">説明</th></tr>
				</thead>
				<tbody>
					<tr><td>アクセストークン</td><td colspan="2"><textarea  name="access_token" class="form-control"></textarea></td></tr>
				</tbody>
			</table>

		</div>
		
		<div>
			<div><button type="button" onclick="get_friends_list()" class="btn btn-primary">一覧</button></div>
			<div id="friends_list"></div>

		</div>
		
		
		<input type="hidden" id="csrf_token" value='{{ csrf_token() }}'; />
    	
    	
   </div></body>
</html>
