<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands\Site;

use App\Entities\Collection;
use App\Entities\ReposNews;
use DB;
use Illuminate\Console\Command;

class SitePushUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:site:push-url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Site Push Url';

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
        $urls = [];
        // category
        $posts = DB::table('categories')->orderBy('created_at', 'desc')->get();
        foreach ($posts as $post) {
            $urls[] = url('category', [$post->slug]);
        }

        // collections
        $collections = Collection::where('is_enable', 1)->orderBy('created_at', 'desc')->get();
        foreach ($collections as $collection) {
            $urls[] = url('collection', [$collection->slug]);
        }

        // sites
        $urls[] = url('sites');

        // list
        $urls[] = url('list/newest');
        $urls[] = url('developers');

        // news
        $urls[] = url('news');
        $news = ReposNews::query()->select('post_date')->groupBy('post_date')->get();
        foreach ($news as $item) {
            $urls[] = url('news/daily', [$item->post_date]);
        }

        $this->info($this->pushUrl($urls));

        /*
         * Repos
         */

        $total_repos = DB::table('repos')->where('status', 1)->count();

        $perPage = 300000;
        $page = ceil($total_repos / $perPage);

        foreach (range(1, $page) as $_page) {
            // get all repos from db (or wherever you store them)
            $repos = DB::table('repos')->select(['id', 'slug', 'updated_at'])->where('status', 1)
                ->forPage($_page, $perPage)->get();

            // counters
            $counter = 0;
            $sitemapCounter = 0;

            // add every product to multiple sitemaps with one sitemapindex
            foreach ($repos as $p) {
                if ($counter == 50000) {
                    $this->info($this->pushUrl($urls));
                    $urls = [];
                    // reset the counter
                    $counter = 0;
                    // count generated sitemap
                    $sitemapCounter++;
                }

                // add product to items array
                $urls[] = url('repos', [$p->slug]);
                // count number of elements
                $counter++;
            }

        }

        /*
         * Developer
         */

        $developer = DB::table('developer')->select(['id', 'login', 'updated_at'])->where('status', 1)
            ->where('public_repos', '>', 0)->orderBy('created_at', 'desc')->get();

        // counters
        $counter = 0;
        $sitemapCounter = 0;

        // add every product to multiple sitemaps with one sitemapindex
        foreach ($developer as $p) {
            if ($counter == 50000) {
                $this->pushUrl($urls);
                $urls = [];
                // reset the counter
                $counter = 0;
                // count generated sitemap
                $sitemapCounter++;
            }

            // add product to items array
            $urls[] = url('developer', [$p->login]);
            // count number of elements
            $counter++;
        }

        /*
         * Questions
         */

        $repos = DB::table('repos')->select(['id', 'slug', 'updated_at'])->where('status', 1)
            ->where('have_questions', 1)->get();

        // counters
        $counter = 0;
        $sitemapCounter = 0;

        // add every product to multiple sitemaps with one sitemapindex
        foreach ($repos as $p) {
            if ($counter == 50000) {
                $this->pushUrl($urls);
                $urls = [];
                // reset the counter
                $counter = 0;
                // count generated sitemap
                $sitemapCounter++;
            }

            // add product to items array
            $urls[] = url("repos/$p->slug/questions");
            // count number of elements
            $counter++;
        }

        $this->info('All done!');
    }

    /**
     * @param array $urls
     * @return mixed
     */
    private function pushUrl(array $urls)
    {
        $api = env('PUSH_URL_API');
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $api,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $urls),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        return $result;
    }
}
