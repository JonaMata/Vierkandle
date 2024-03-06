<?php

namespace App\Console\Commands;

use App\Models\Vierkandle;
use Illuminate\Console\Command;

class DailyPuzzles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-puzzles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle() : void
    {
        info('Creating daily puzzles.');
        $vierkandle = new Vierkandle();
        $vierkandle->date = now()->addDay();
        $vierkandle->is_daily = true;
        $vierkandle->generate(4);
        $express = new Vierkandle();
        $express->date = now()->addDay();
        $express->is_daily = true;
        $express->is_express = true;
        $express->generate(3);
        info('Created vierkandle for tomorrow.');
        info('Done.');
    }
}
