<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PackageProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devhub:package:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Package Process';

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
        //
    }
}
