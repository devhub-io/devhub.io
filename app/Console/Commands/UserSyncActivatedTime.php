<?php

/*
 * This file is part of devhub.io.
 *
 * (c) DevelopHub <master@devhub.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands;

use App\Entities\User;
use Cache;
use Illuminate\Console\Command;

class UserSyncActivatedTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:user:sync-activated-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User Sync activated time';

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
        $data = Cache::pull('activated_time_for_update');
        if(!$data){
            $this->error('Error: No Data!');
            return false;
        }

        foreach ($data as $user_id => $last_activated_at) {
            User::query()->where('id', $user_id)
                         ->update(['last_activated_at' => $last_activated_at]);
        }

        $this->info('Done!');
        return true;
    }
}
