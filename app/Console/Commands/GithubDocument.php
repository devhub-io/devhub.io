<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class GithubDocument extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:github:document';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Github Document';

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
        $repos = DB::table('repos')->select(['id', 'owner', 'repo'])->where('document_url', '')->where('language', 'go')->get();
        foreach ($repos as $item) {
            $document_url = "https://godoc.org/github.com/$item->owner/$item->repo";
            DB::table('repos')->where('id', $item->id)->update([
                'document_url' => $document_url
            ]);
            $this->info($document_url);
        }
        $this->info('All done!');
    }
}
