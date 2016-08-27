<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Jobs;

use Embed\Embed;
use Carbon\Carbon;
use App\Entities\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ArticleFetch implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var
     */
    protected $article_id;

    /**
     * Create a new job instance.
     *
     * @param $url
     * @param $article_id
     */
    public function __construct($url = '', $article_id = 0)
    {
        $this->url = $url;
        $this->article_id = $article_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $info = Embed::create($this->url);
        if ($this->article_id) {
            $article = Article::find($this->article_id);
            $article->title = $info->getTitle();
            $article->description = $info->getDescription();
            $article->fetched_at = Carbon::now();
            $article->save();
        } else {
            if (!$ex = Article::query()->where('url', $this->url)->first()) {
                Article::create([
                    'title' => $info->getTitle(),
                    'description' => $info->getDescription(),
                    'url' => $this->url,
                    'fetched_at' => Carbon::now(),
                ]);
            }
        }
    }
}
