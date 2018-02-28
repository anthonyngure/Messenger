<?php
	
	use Illuminate\Database\Seeder;
	
	class SubscriptionsTableSeeder extends Seeder
	{
		/**
		 * Run the database seeds.
		 *
		 * @return void
		 */
		public function run()
		{
			//
			\App\Subscription::create(['name' => 'Daily Nation', 'type' => 'NEWSPAPER',]);
			\App\Subscription::create(['name' => 'The Standard', 'type' => 'NEWSPAPER',]);
			\App\Subscription::create(['name' => 'The Star', 'type' => 'NEWSPAPER',]);
			\App\Subscription::create(['name' => 'The EastAfrican', 'type' => 'NEWSPAPER',]);
			\App\Subscription::create(['name' => 'Business Daily Africa', 'type' => 'NEWSPAPER',]);
			\App\Subscription::create(['name' => 'Taifa Leo', 'type' => 'NEWSPAPER',]);
			\App\Subscription::create(['name' => 'Kenya Times', 'type' => 'NEWSPAPER',]);
			\App\Subscription::create(['name' => 'Kenya Gazzette', 'type' => 'NEWSPAPER',]);
		}
	}
