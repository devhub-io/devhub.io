<?php

namespace App\Console\Commands;

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

        if (!in_array($provider, ['atom', 'sublime'])) {
            $this->error('Error name');
            return;
        }

        $path = $this->argument('path');
        $path = base_path($path);

        try {
            $json = file_get_contents($path);
            $data = json_decode($json, true);

            foreach ($data as $item) {
                if (!DB::table('packages')->where('provider', $provider)->where('name', $item['name'])->exists()) {
                    DB::table('packages')->insert([
                        'provider' => $provider,
                        'name' => $item['name'],
                        'repository' => '',
                        'json' => json_encode($item),
                        'fetched_at' => Carbon::now(),
                    ]);

                    $this->info("Insert {$item['name']}");
                } else {
                    $this->info("Pass {$item['name']}");
                }
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
