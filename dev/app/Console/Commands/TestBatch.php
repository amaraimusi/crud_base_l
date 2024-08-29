<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-batch';

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

		
		echo 'バッチ処理を終了しました。';
    }
}
