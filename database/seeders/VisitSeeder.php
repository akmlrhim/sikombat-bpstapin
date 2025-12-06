<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class VisitSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		$faker = Faker::create();

		$users = User::all()->pluck('id')->toArray();

		$browser = [
			"Chrome",
			"Firefox",
			"Safari",
			"Edge",
			"Opera",
			"Brave",
			"Samsung Internet",
			"UC Browser",
			"Vivaldi",
			"Internet Explorer",
			"Yandex Browser",
			"Maxthon",
			"QQ Browser",
			"Baidu Browser",
			"KaiOS Browser",
		];


		$visits = [];

		// Buat 200 pengunjung login
		for ($i = 0; $i < 600; $i++) {
			$visits[] = [
				'user_id' => $faker->randomElement($users),
				'ip' => $faker->ipv4,
				'browser' => $faker->randomElement($browser),
				'created_at' => $faker->dateTimeBetween('-30 days', 'now'),
				'updated_at' => now(),
			];
		}

		// Insert ke database sekaligus
		DB::table('visits')->insert($visits);
	}
}
