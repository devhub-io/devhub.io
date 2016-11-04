<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class ReposFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:repos:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repos Fix';

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
        $this->info('Fix .php');
        $repos = DB::table('repos')->where('slug', 'like', '%.php')->select(['id', 'slug'])->get();
        foreach ($repos as $item) {
            $slug = str_replace('.php', '_php', $item->slug);
            DB::table('repos')->where('id', $item->id)->update(['slug' => $slug]);
            $this->info('Repos: ' . $item->id);
        }
    }
}
