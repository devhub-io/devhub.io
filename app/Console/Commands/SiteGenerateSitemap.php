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

use App;
use App\Entities\Collection;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class SiteGenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'develophub:site:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Site Generate Sitemap';

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
        // create new sitemap object
        $sitemap = App::make("sitemap");

        $sitemap->add(url('/'), Carbon::now(), '1.0', 'daily');

        // category
        $posts = DB::table('categories')->orderBy('created_at', 'desc')->get();
        foreach ($posts as $post) {
            $sitemap->add(url('category', [$post->slug]), $post->updated_at, '1.0', 'daily');
        }

        // collections
        $collections = Collection::where('is_enable', 1)->orderBy('created_at', 'desc')->get();
        foreach ($collections as $collection) {
            $sitemap->add(url('collection', [$collection->slug]), $collection->updated_at, '1.0', 'daily');
        }

        // sites
        $sitemap->add(url('sites'), Carbon::now(), '1.0', 'daily');

        // list
        $sitemap->add(url('list/newest'), Carbon::now(), '1.0', 'daily');
        $sitemap->add(url('developers'), Carbon::now(), '1.0', 'daily');

        /*
         * Repos
         */

        // get all repos from db (or wherever you store them)
        $repos = DB::table('repos')->select(['id', 'slug', 'updated_at'])->where('status', 1)->orderBy('created_at', 'desc')->get();

        // counters
        $counter = 0;
        $sitemapCounter = 0;

        // add every product to multiple sitemaps with one sitemapindex
        foreach ($repos as $p) {
            if ($counter == 50000) {
                // generate new sitemap file
                $sitemap->store('xml', 'sitemap-repos-' . $sitemapCounter);
                // add the file to the sitemaps array
                $sitemap->addSitemap(secure_url('sitemap-repos-' . $sitemapCounter . '.xml'));
                // reset items array (clear memory)
                $sitemap->model->resetItems();
                // reset the counter
                $counter = 0;
                // count generated sitemap
                $sitemapCounter++;
            }

            // add product to items array
            $sitemap->add(url('repos', [$p->slug]), $p->updated_at, '1.0', 'daily');
            // count number of elements
            $counter++;
        }

        // you need to check for unused items
        if (!empty($sitemap->model->getItems())) {
            // generate sitemap with last items
            $sitemap->store('xml', 'sitemap-repos-' . $sitemapCounter);
            // add sitemap to sitemaps array
            $sitemap->addSitemap(secure_url('sitemap-repos-' . $sitemapCounter . '.xml'));
            // reset items array
            $sitemap->model->resetItems();
        }

        /*
         * Developer
         */

        $developer = DB::table('developer')->select(['id', 'login', 'updated_at'])->where('status', 1)->orderBy('created_at', 'desc')->get();

        // counters
        $counter = 0;
        $sitemapCounter = 0;

        // add every product to multiple sitemaps with one sitemapindex
        foreach ($developer as $p) {
            if ($counter == 50000) {
                // generate new sitemap file
                $sitemap->store('xml', 'sitemap-developer-' . $sitemapCounter);
                // add the file to the sitemaps array
                $sitemap->addSitemap(secure_url('sitemap-developer-' . $sitemapCounter . '.xml'));
                // reset items array (clear memory)
                $sitemap->model->resetItems();
                // reset the counter
                $counter = 0;
                // count generated sitemap
                $sitemapCounter++;
            }

            // add product to items array
            $sitemap->add(url('developer', [$p->login]), $p->updated_at, '1.0', 'daily');
            // count number of elements
            $counter++;
        }

        // you need to check for unused items
        if (!empty($sitemap->model->getItems())) {
            // generate sitemap with last items
            $sitemap->store('xml', 'sitemap-developer-' . $sitemapCounter);
            // add sitemap to sitemaps array
            $sitemap->addSitemap(secure_url('sitemap-developer-' . $sitemapCounter . '.xml'));
            // reset items array
            $sitemap->model->resetItems();
        }

        // generate new sitemapindex that will contain all generated sitemaps above
        $sitemap->store('sitemapindex', 'sitemap');
    }
}
