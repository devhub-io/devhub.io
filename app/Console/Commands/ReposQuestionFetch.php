<?php

namespace App\Console\Commands;

use App\Entities\Repos;
use App\Entities\Service;
use App\Support\StackExchange;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class ReposQuestionFetch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:repos:question-fetch {page} {perPage}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Repos Question Fetch';

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
        $stackexchange = Service::query()->where('provider', 'stackexchange')->where('user_id', 1)->first();
        if ($stackexchange) {
            $client = new StackExchange($stackexchange->token);

            $last_quota_remaining = 0;
            $page = $this->argument('page');
            $perPage = $this->argument('perPage');
            $repos = Repos::query()->select(['id', 'repo'])->orderBy('stargazers_count', 'desc')->forPage($page, $perPage)->get();
            foreach ($repos as $repo) {

                $res = $client->faq($repo->repo);
                $last_quota_remaining = (int)$res['quota_remaining'];
                if ($last_quota_remaining < 1) {
                    $this->error('Quota remaining 0');
                    return 0;
                }

                $this->info("-------- $repo->repo --------");
                if (count($res['items']) > 0) {
                    foreach ($res['items'] as $question) {
                        if (!DB::table('repos_questions')->where('repos_id', $repo->id)->where('question_id', $question['question_id'])->exists()) {
                            DB::table('repos_questions')->insert([
                                'repos_id' => $repo->id,
                                'title' => $question['title'],
                                'link' => $question['link'],
                                'view_count' => $question['view_count'],
                                'answer_count' => $question['answer_count'],
                                'score' => $question['score'],
                                'question_id' => $question['question_id'],
                                'creation_date' => Carbon::createFromTimestampUTC($question['creation_date']),
                                'last_edit_date' => isset($question['last_edit_date']) ? Carbon::createFromTimestampUTC($question['last_edit_date']) : null,
                                'last_activity_date' => isset($question['last_activity_date']) ? Carbon::createFromTimestampUTC($question['last_activity_date']) : null,
                            ]);

                            $this->info($question['title']);
                        } else {
                            $this->info('pass ' . $question['title']);
                        }
                    }
                    DB::table('repos')->where('id', $repo->id)->update(['have_questions' => true]);
                } else {
                    DB::table('repos')->where('id', $repo->id)->update(['have_questions' => false]);
                }
            }

            $this->info("Quota remaining: $last_quota_remaining");
            $this->info('All done!');
        }
    }
}
