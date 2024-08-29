<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use CrudBase\CrudBase;

class MonitorBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:monitor-batch';

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

        // famous_catsテーブルのデータ件数を取得
        $catCount = DB::table('famous_cats')->count();

        // famous_catsテーブルの全データ中の最新更新日時を取得
        $latestUpdate = DB::table('famous_cats')->max('updated_at');
		
		// データがならデフォルトデータをテーブルにINSERTする。
		$this->ifEmptyInsertDef();
		
		// monitorsテーブルから最新の監視エンティティ（レコード）を1件取得
        $latestMonitor = DB::table('monitors')
            ->orderBy('updated_at', 'desc')
            ->first();

        // 結果をコンソールに出力
        $this->info("famous_catsテーブルのデータ件数: {$catCount}");
        $this->info("famous_catsテーブルの最新更新日時: {$latestUpdate}");
        $this->info("監視テーブルのデータ件数: {$latestMonitor->monitor_data_count}");
        $this->info("監視テーブルの最新更新日時: {$latestMonitor->monitor_updated}");
		
		// famous_catsテーブルのデータ件数と監視データ件数が不一致、または最新更新日時が監視対象更新日時より新しい場合
		if($catCount != $latestMonitor->monitor_data_count || $latestUpdate != $latestMonitor->monitor_updated){
			$this->info("データが変更されているのでデータを復元します。");
			
			// famous_catsテーブルのデータをすべて削除する
			DB::table('famous_cats')->truncate();
			
			// famous_catsテーブルのオートインプリメントをリセットする
			DB::statement('ALTER TABLE famous_cats AUTO_INCREMENT = 1');
			
			// famous_cats_reテーブルの全データをfamous_catsテーブルにINSERTする
			DB::statement('INSERT INTO famous_cats SELECT * FROM famous_cats_re');
			
			$this->info("データベースのデータを復元しました。");
			
			// 「storage/famous_cat」を中身のファイルごと削除する
			$rm_dir = public_path('storage/famous_cat');
			if (is_dir($rm_dir)) {
				$this->rmdirEx($rm_dir);
				$this->info("リソースの画像を一旦削除しました");
			}
			
			// 「rsc/repare/famous_cat」ディレクトリを「storage」ディレクトリにコピーする
			$source_dir = public_path('rsc/repare/famous_cat');
			$dest_dir = public_path('storage/famous_cat');
			$this->copyDirEx($source_dir, $dest_dir);
			$this->info("リソースの画像を復元しました。");
			
			$newCatCount = DB::table('famous_cats')->count();
			
			// monitorsテーブルから最新の監視エンティティ（レコード）を1件取得
			$newLatestUpdate = DB::table('famous_cats')->max('updated_at');
			
			//	変更確認・監視エンティティを作成し、監視テーブルへ追加
			//		監視対象更新日時と監視データ件数は監視エンティティからセットする
			//		メモにデータ変更があった旨をセット。最新更新日やデータ件数など。
			$mEnt2 = [
					'monitor_updated' => $newLatestUpdate,
					'monitor_data_count' => $newCatCount,
					'monitor_counter' => 0,
					'memo' => "データ変更あり:更新日付=>{$latestUpdate} データ件数=>{$catCount}",
					'sort_no' => 0,
					'updated_at' => date('Y-m-d H:i:s'), // 現在の日時を設定
			];
			
			//	常時チェック用の監視エンティティを作成
			//		監視対象更新日時と監視データ件数は監視エンティティからセットする。
			//		更新日時もセット
			$mEnt3 = [
					'monitor_updated' => $newLatestUpdate,
					'monitor_data_count' => $newCatCount,
					'monitor_counter' => 0,
					'memo' => "none",
					'sort_no' => 0,
					'updated_at' => date('Y-m-d H:i:s'), // 現在の日時を設定
			];
			
			DB::table('monitors')->insert($mEnt3);
			
			$this->info("データ変更されたため、データを元に戻しました。");
	
		}else{
			$latestMonitor->monitor_counter++;
			
			// $latestMonitorをmonitorsテーブルにUPDATEする
			DB::table('monitors')
				->where('id', $latestMonitor->id) // ここでidを指定
				->update((array)$latestMonitor);
			
			$this->info("データ変更なし。監視回数をカウントしDB更新しました。");
		}
		
		$this->info("処理終了");
		
    }
	
	// データがからならデフォルトデータをテーブルにINSERTする。
	private function ifEmptyInsertDef(){
		
		$monitorCount = DB::table('monitors')->count();

		if ($monitorCount === 0) {
			// monitorsテーブルにデータが0件の場合の処理

			$defMonitorEnt = [
					'monitor_updated' => '2024-08-27 21:50:23',
					'monitor_data_count' => 218,
					'monitor_counter' => 0,
					'memo' => 'none',
					'sort_no' => 0,
					'updated_at' => date('Y-m-d H:i:s'), // 現在の日時を設定
			];
		
			DB::table('monitors')->insert($defMonitorEnt);
			$this->info("監視テーブルのデータが空なので1件追加しました。");
			
		}
		
	}
	
	
    /**
     * 指定したディレクトリを再帰的に削除するメソッド
     * 
     * @param string $dir 削除対象のディレクトリのパス
     * @throws InvalidArgumentException
     */
    public function rmdirEx($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $this->deleteDirectoryContents($dir);

        // 最後にディレクトリ自体を削除
        rmdir($dir);
    }

    /**
     * ディレクトリの中身を再帰的に削除する
     * 
     * @param string $dir
     */
    private function deleteDirectoryContents($dir)
    {
        $items = scandir($dir);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_dir($path)) {
                // サブディレクトリの場合、再帰的に削除
                $this->rmdirEx($path);
            } else {
                // パーミッションを変更してファイルを削除
                if (!is_writable($path)) {
                    chmod($path, 0666);
                }
                unlink($path);
            }
        }
    }
	
	
	 /**
     * 再帰的にディレクトリをコピーするメソッド
     *
     * @param string $sourceDir コピー元のディレクトリパス
     * @param string $destDir コピー先のディレクトリパス
     * @return bool コピーが成功した場合は true、失敗した場合は false
     */
    public function copyDirEx(string $sourceDir, string $destDir): bool
    {
        // コピー元のディレクトリが存在しない場合、falseを返す
        if (!is_dir($sourceDir)) {
            return false;
        }

        // コピー先のディレクトリが存在しない場合、作成する
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        // ディレクトリハンドルを開く
        $dirHandle = opendir($sourceDir);
        if ($dirHandle === false) {
            return false;
        }

        // ディレクトリ内のファイルやフォルダをループ
        while (($file = readdir($dirHandle)) !== false) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $sourcePath = $sourceDir . DIRECTORY_SEPARATOR . $file;
            $destPath = $destDir . DIRECTORY_SEPARATOR . $file;

            if (is_dir($sourcePath)) {
                // 再帰的にディレクトリをコピー
                if (!$this->copyDirEx($sourcePath, $destPath)) {
                    closedir($dirHandle);
                    return false;
                }
            } else {
                // ファイルをコピー
                if (!copy($sourcePath, $destPath)) {
                    closedir($dirHandle);
                    return false;
                }
            }
        }

        // ディレクトリハンドルを閉じる
        closedir($dirHandle);
        return true;
    }
		
		
		
}
