<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands\Repos;

use DB;
use Exception;
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
            try {
                DB::table('repos')->where('id', $item->id)->update(['slug' => $slug]);
                $this->info('Repos: ' . $item->id);
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }
}
