<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Venturecraft\Revisionable\RevisionableTrait;

class Repos extends Model implements Transformable
{
    use TransformableTrait;
    use RevisionableTrait;
    use Searchable;

    /**
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'description', 'language', 'homepage', 'github', 'stargazers_count', 'watchers_count',
        'open_issues_count', 'forks_count', 'subscribers_count', 'repos_created_at', 'repos_updated_at', 'fetched_at',
        'readme', 'image', 'category_id', 'user_id', 'issue_response', 'is_recommend', 'view_number'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Entities\Category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Entities\User');
    }

    /**
     * @return integer
     */
    public function overall()
    {
        return $this->stargazers_count + $this->forks_count + $this->subscribers_count;
    }

    /**
     * @return void
     */
    public function update_trend()
    {
        $now = Carbon::now();
        $today_trend = ReposTrend::where('repos_id', $this->id)->where('date', $now->toDateString())->first();
        if ($today_trend) {
            $today_trend->overall = $this->overall();
            $today_trend->save();
        } else {
            $today_trend = ReposTrend::create([
                'repos_id' => $this->id,
                'date' => $now->toDateString(),
                'overall' => $this->overall(),
                'trend' => 0,
            ]);
        }

        $prev_trend = ReposTrend::where('repos_id', $this->id)->where('id', '<', $today_trend->id)->orderBy('id', 'desc')->first();
        if ($prev_trend) {
            $diffDay = $now->diffInDays(Carbon::parse($prev_trend->date));
            if ($diffDay > 0) {
                $trend = ($this->overall() - $prev_trend->overall) / $now->diffInDays(Carbon::parse($prev_trend->date));
            } else {
                $trend = 0;
            }
        } else {
            $trend = 0;
        }

        $today_trend->trend = $trend;
        $today_trend->save();

        // trends
        $repos_trends = ReposTrend::where('repos_id', $this->id)->orderBy('date')->limit(8)->get();
        if ($repos_trends) {
            $trends = [];
            foreach ($repos_trends as $item) {
                $trends[] = $item->trend;
            }
            $this->trends = implode(',', $trends);
            $this->save();
        }
    }
}
