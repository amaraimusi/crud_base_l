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
	protected $signature = 'batch:test';
	
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
		
		$model = new Neko();
		$ent = $model->find(77);
		dump($ent);//■■■□□□■■■□□□)
		
		
		echo 'バッチ処理を終了しました。';
	}
}
