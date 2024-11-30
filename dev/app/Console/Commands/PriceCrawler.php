<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate¥Support¥Facades¥DB; // DBを扱えるようにする

use App\Models\Neko;

class PriceCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:price_crawler{url}';

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
        echo 'クローラーバッチ処理を開始します。';
        
        $url = $this->argument('url'); // 引数名と一致させる
        echo $url;
        
        
        
        // エラーレポートの設定
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        // cURLセッションの初期化
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.0.0 Safari/537.36');
        
        // SSL検証を無効化（テスト用。可能であれば有効にするべき）
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        // HTMLを取得
        $html = curl_exec($ch);
        
        // エラーを取得
        if ($html === false) {
        	$error = curl_error($ch);
        	curl_close($ch);
        	die("cURLエラーが発生しました: " . $error);
        }
        
        curl_close($ch);
        
        
        // エラー処理
        if ($html === false) {
        	die("HTMLの取得に失敗しました。");
        }
        
        // HTMLのエンコーディングを取得
        $encoding = mb_detect_encoding($html, ['UTF-8', 'Shift_JIS', 'EUC-JP', 'ISO-8859-1'], true);
        
        // 必要であればUTF-8に変換
        if ($encoding !== 'UTF-8') {
        	$html = mb_convert_encoding($html, 'UTF-8', $encoding);
        }
        
        //echo $html;
        echo PHP_EOL;
        
        $pattern = '/class="p-PTShopData_name_link">(.*?)<\/a>/';
        
        // マッチしたショップ名を取得
        if (preg_match_all($pattern, $html, $matches)) {
        	$shopNames = $matches[1]; // マッチ結果の第2要素がショップ名
        	foreach ($shopNames as $shopName) {
        		echo "ショップ名: " . $shopName . PHP_EOL;
        	}
        } else {
        	echo "ショップ名が見つかりませんでした。";
        }

//         // DOMDocumentを利用してHTMLを解析
//         $dom = new \DOMDocument();
//         libxml_use_internal_errors(true); // HTML構文エラーを無視
//         $dom->loadHTML($html);
//         libxml_clear_errors();
        
//         echo 'DOMの中身' . PHP_EOL;
//         echo $dom->saveHTML(); // DOMDocumentのHTMLを出力
//         echo PHP_EOL;
        
//         // XPathを使用してデータを取得
//         $xpath = new \DOMXPath($dom);
        
//         $nodes = $xpath->query('//div'); // すべての<div>を取得
//         foreach ($nodes as $node) {
//         	echo $node->ownerDocument->saveHTML($node); // 各ノードのHTMLを表示
//         	echo PHP_EOL;
//         }
        
//         // 価格とショップ名の取得
//         $priceNodes = $xpath->query('//table[@class="p-priceTable"]//p[@class="p-PTPrice_price"]');
        
//         //$shopNodes = $xpath->query('//table[@class="p-priceTable"]//p[@class="p-PTShopData_name"]/a');
//         $shopNodes = $xpath->query('//a[@class="p-PTShopData_name_link"]');
        
//         echo  $shopNodes->length . PHP_EOL;
//         echo  $priceNodes->length . PHP_EOL;
        
//         // リストを生成
//         $results = [];
//         for ($i = 0; $i < $priceNodes->length && $i < $shopNodes->length; $i++) {
//         	$price = trim($priceNodes->item($i)->textContent);
//         	$shop = trim($shopNodes->item($i)->textContent);
//         	$results[] = "$price\t$shop";
//         }
        
//         // 結果を出力
//         foreach ($results as $result) {
//         	echo $result . PHP_EOL;
//         }
        
//         // 例: ページタイトルを取得
//         $titleNode = $xpath->query('//title');
//         $title = $titleNode->length > 0 ? $titleNode->item(0)->nodeValue : "タイトルが取得できませんでした";
        
//         // 結果を出力
//         echo "ページタイトル: " . $title . PHP_EOL;
        
        
        //$model = new Neko();
        //$ent = $model->find(77);
        //dump($ent);//■■■□□□■■■□□□)

		
		echo 'バッチ処理を終了しました。';
    }
}
