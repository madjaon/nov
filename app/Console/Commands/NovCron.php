<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Helpers\CommonPost;

class NovCron extends Command
{
	/**
	* The name and signature of the console command.
	*
	* @var string
	*/
	protected $signature = 'nov:cron';

	/**
	* The console command description.
	*
	* @var string
	*/
	protected $description = 'Command description';

	/**
	* Create a new command instance.
	*
	* @return void
	*/
	public function __construct()
	{
		parent::__construct();
	}

	/**
	* Execute the console command.
	*
	* @return mixed
	*/
	public function handle()
	{
		$postContinue = self::getPostContinue();
		if(!empty($postContinue)) {
			CommonPost::insertChapsByPosts($postContinue);
		} else {
			$this->info('Nov:Cron No Data Found!');  
		}
		$this->info('Nov:Cron Command Run successfully!');
	}

	private function getPostContinue()
	{
		$data = DB::table('posts')
				->where('source', 'truyenfull')
				->where('kind', SLUG_POST_KIND_UPDATING)
				->where('status', ACTIVE)
				->where('start_date', '<=', date('Y-m-d H:i:s'))
				->lists('source_url', 'id');
		return $data;
	}

}
