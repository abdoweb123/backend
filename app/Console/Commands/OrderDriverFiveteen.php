<?php

namespace App\Console\Commands;

use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Console\Command;

class OrderDriverFiveteen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:DriverFiveteen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {                           

        $TripSchedule=Trip::where('schedule_at','<',Carbon::now()->addMinutes(15))->first();
        echo ($TripSchedule);

    }
}
