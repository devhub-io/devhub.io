<?php

/*
 * This file is part of devhub.io.
 *
 * (c) sysatom <sysatom@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Console\Commands\Package;

use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Console\Command;

class PackageImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:package:import {provider} {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Package Import';

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
        $provider = $this->argument('provider');

        if (!in_array($provider, ['atom', 'sublime', 'chocolatey', 'dub', 'elm', 'hackage', 'haxe', 'opam', 'pypi'])) {
            $this->error('Error name');
            return;
        }

        $path = $this->argument('path');
        $path = base_path($path);

        try {
            $json = file_get_contents($path);
            $data = json_decode($json, true);

            foreach ($data as $item) {
                if (!DB::table('packages')->where('provider', $provider)->where('package_url', $item['url'])->exists()) {
                    DB::table('packages')->insert([
                        'provider' => $provider,
                        'name' => $item['name'],
                        'repository' => '',
                        'package_url' => $item['url'],
                        'json' => json_encode($item),
                        'fetched_at' => Carbon::now(),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    $this->info("Insert {$item['url']}");
                } else {
                    $this->info("Pass {$item['url']}");
                }
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
