<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands;

use DB;
use App\Entities\Repos;
use Illuminate\Console\Command;

class SettingReposCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:setting-repos-category';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setting Repos category';

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
        $repos = Repos::query()->where('category_id', 0)->select('id', 'category_id', 'language')->get();
        foreach ($repos as $item) {
            $language = strtolower($item->language);
            if ($language == 'c++') {
                $language = 'cpp';
            } else if ($language == 'c#') {
                $language = 'c-sharp';
            } else if ($language == 'objective-c++') {
                $language = 'objective-cpp';
            }
            $category = DB::table('categories')->where('slug', $language)->first();
            if ($category) {
                $item->category_id = $category->id;
                $item->save();
                $this->info('Repos:' . $item->id . ' -> Category:' . $category->id);
            } else {
                $this->info($item->id);
            }
        }
        $this->info('All Done!');
    }
}
