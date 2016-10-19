<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class DeveloperRating extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:developer:rating';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Developer Rating';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $developers = DB::table('developer')->select(['id', 'login'])->orderBy('followers', 'desc')->get();
        foreach ($developers as $developer) {
            $repos = DB::table('repos')->select(DB::raw('count(1) as number, sum(stargazers_count) as stars'))->where('owner', $developer->login)->first();
            if ($repos && $repos->number > 0) {
                $rating = $repos->stars + (1.0 - 1.0 / $repos->number);
                DB::table('developer')->where('id', $developer->id)->update([
                    'rating' => $rating,
                ]);
            }
            $this->info($developer->login);
        }
        $this->info('All done!');
    }
}
