<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Admin\Log;
use Carbon\Carbon;

class ClearSystemLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'light:clearSystemLog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear system log';

    /**
     * Create a new command instance.
     *
     * @return void
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
        Log::where('created_at', '<', Carbon::now()->subDays(config('light.log_reserve_days', 180)))->delete();
    }
}
