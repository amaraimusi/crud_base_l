<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Client; // Google API Clientのクラスをインポート
use Google\Service\CustomSearchAPI; // CustomSearchAPIのクラスをインポート
use Exception; // Exceptionクラスもインポート

class TestBatch extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'batch:gcs';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';
	
	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		echo 'バッチ処理を開始します。' . PHP_EOL;
		
		//$API_KEY = 'AIzaSyABizD9DfPTi4W_LWZXvO-K9O8qkIM8XKs'; // 取得したAPIキーを入力
		$API_KEY = env('GCS_API_KEY');
		$SEARCH_ENGINE_ID = '75318d372d5e94f5b'; // 取得した検索エンジンIDを入力
		
		
		try {
			// Google API Clientを設定
			$client = new Client();
			$client->setApplicationName('Your Application Name');
			$client->setDeveloperKey($API_KEY);
			
			$service = new CustomSearchAPI($client);
			
			// 本日と昨日の日付を取得
			$today = date('Y-m-d');
			$yesterday = date('Y-m-d', strtotime('-1 day'));
			
			$query = 'トレンド商品 ニュース';
			
			// クエリに期間指定を追加
			$query = $query . " after:$yesterday before:$today";
			
			$optParams = [
					'cx' => $SEARCH_ENGINE_ID,
					'q' => $query,
					'num' => 10, // 取得する結果の数
			];
			
			$results = $service->cse->listCse($optParams);
			foreach ($results->getItems() as $item) {
				echo 'タイトル: ' . $item['title'] . PHP_EOL;
				echo 'リンク: ' . $item['link'] . PHP_EOL;
				echo '概要: ' . $item['snippet'] . PHP_EOL . PHP_EOL;
			}
		} catch (Exception $e) {
			echo 'エラーが発生しました: ' . $e->getMessage() . PHP_EOL;
		}
		
		echo 'バッチ処理を終了しました。' . PHP_EOL;
	}
}
