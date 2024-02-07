<?php

namespace App\Http\Controllers;

use App\Consts\crud_base_function;
use Illuminate\Http\Request;
use App\Models\LineDemo;
use CrudBase\CrudBase;
use App\Consts\ConstCrudBase;

/**
 * LINEデモ
 * @since 2024-1-29
 * @version 1.0.0
 * @author amaraimusi
 *
 */
class LineDemoController extends CrudBaseController{

	
	/**
	 * indexページのアクション
	 *
	 * @param  Request  $request
	 * @return \Illuminate\View\View
	 */
	public function index(Request $request){

		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');
		
        
		return view('line_demo.index', []);
		
	}
	
	
	/**
	 * indexページのアクション
	 *
	 * @param  Request  $request
	 * @return \Illuminate\View\View
	 */
	public function audience(Request $request){
		
		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');
		
		
		return view('line_demo.audience', []);
		
	}
	
	
	public function audience_list(){
		
		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');
		
		$json=$_POST['key1'];
		$param = json_decode($json, true);
		
		$accessToken = $param['access_token']; // LINEのアクセストークン
		$url = 'https://api.line.me/v2/bot/audienceGroup/list';
		
		$headers = [
				'Authorization: Bearer ' . $accessToken,
				'Content-Type: application/json'
		];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($ch);
		curl_close($ch);
		
		$audienceData=json_decode($response, true);//JSONデコード
		$res = ['audienceData' => $audienceData];

		$json = json_encode($res, JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_APOS);
		return $json;
		
	}
	

	public function audience_reg(){

		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');

		$json=$_POST['key1'];
		
		$param = json_decode($json, true);
		

		$description = $param['description']; // オーディエンス名
		$isIfaAudience = $param['isIfaAudience']; // IFAフラグ
		$uploadDescription = $param['uploadDescription']; // ジョブ説明
		$audiences = $param['audiences']; // ユーザー名リスト

		
		
		$accessToken = $param['access_token']; // LINEのアクセストークン
		$url = 'https://api.line.me/v2/bot/audienceGroup/upload';
		
		$headers = [
				'Authorization: Bearer ' . $accessToken,
				'Content-Type: application/json'
		];
		
		$data = [
				'description' => $description,
				'isIfaAudience' => $isIfaAudience,
				'audiences' => [
// 						['id' => 'USER_ID_1'],
// 						['id' => 'USER_ID_2'],
// 						// 他のユーザーIDを追加...
				],
		];
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($ch);
		curl_close($ch);
		
		dump($response);//■■■□□□■■■□□□)

		$json = json_encode($response, JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_APOS);
		
		return $json;
	}
	
	
	/**
	 * 友だちユーザー一覧 friends_list
	 *
	 * @param  Request  $request
	 * @return \Illuminate\View\View
	 */
	public function friends_list(Request $request){
		
		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');
		
		
		return view('line_demo.friends_list', []);
		
	}
	
	
	public function get_friends_list(){
		
		// ログアウトになっていたらログイン画面にリダイレクト
		if(\Auth::id() == null) return redirect('login');
		
		$json=$_POST['key1'];
		$param = json_decode($json, true);
		
		$accessToken = $param['access_token']; // LINEのアクセストークン
		
		//　認証アカウントでないと以下のAPIは仕様できない。
		//   詳しくは以下のURLを参考：
		//    https://www.lycbiz.com/jp/service/line-official-account/account-type/
		//    https://developers.line.biz/ja/reference/messaging-api/#get-follower-ids
		
		
		
		// LINE Messaging APIのエンドポイント
		$url = 'https://api.line.me/v2/bot/followers/ids';

		// cURLセッションを初期化
		$ch = curl_init($url);
		
		// cURLオプションを設定
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Authorization: Bearer ' . $accessToken
		]);
		
		// リクエストを実行し、レスポンスを取得
		$response = curl_exec($ch);
		
		// エラーがあれば処理
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		
		// cURLセッションを終了
		curl_close($ch);
		
		
		
// 		$url = 'https://api.line.me/v2/bot/audienceGroup/list';
		
// 		$headers = [
// 				'Authorization: Bearer ' . $accessToken,
// 				'Content-Type: application/json'
// 		];
		
// 		$ch = curl_init();
// 		curl_setopt($ch, CURLOPT_URL, $url);
// 		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// 		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
// 		$response = curl_exec($ch);
// 		curl_close($ch);
		
 		$data=json_decode($response, true);//JSONデコード

		//$data =[0=>['neko'=>'ネコ']];
 		dump($data);//■■■□□□■■■□□□)
		$res = ['data' => $data];
		
		$json = json_encode($res, JSON_HEX_TAG | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_APOS);
		return $json;
		
	}
	

}