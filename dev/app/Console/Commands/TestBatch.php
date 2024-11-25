<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate¥Support¥Facades¥DB; // DBを扱えるようにする
use App\Models\Neko;

class TestBatch extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'batch:test {id}'; // コマンド実行例→ $ php artisan batch:test 77
	
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
		echo 'バッチ処理を開始します。';
		
		// 引数を取得
		$id = $this->argument('id'); // 引数名と一致させる
		if(empty($id)) $id = 77;
		
		$model = new Neko();
		$ent = $model->find($id);
		dump($ent);//■■■□□□■■■□□□)
		
		
		echo 'バッチ処理を終了しました。';
	}
}
