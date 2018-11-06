<?php

namespace App\Console\Commands\Jobs;

use App\Entities\Job;
use App\Entities\Repos;
use App\Entities\ReposNews;
use App\Entities\ReposUrl;
use App\Repositories\Constant;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FakerData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:jobs:faker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Jobs Faker data';

    /**
     * Create a new command instance.
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
        $faker = \Faker\Factory::create();
        foreach (range(0, 100) as $item) {
            $d = Carbon::createFromTimeString($faker->iso8601);
            $job = new Job();
            $job->company_name = $faker->company;
            $job->company_logo = $faker->imageUrl($width = 200, $height = 480);
            $job->job_title = $faker->jobTitle;
            $job->job_location = $faker->city;
            $job->url = $faker->url;
            $job->description = $faker->realText(500);
            $job->slug = Str::random(5);
            $job->tags = '';
            $job->coupon = '';
            $job->source = '';
            $job->status = 1;
            $job->created_at = $d;
            $job->updated_at = $d->addDay();
            $job->save();
        }
        $this->info('All done!');
    }
}
